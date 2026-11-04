<?php

namespace App\Services\v1\management\billing;

use App\DTOs\v1\management\billing\payments\PaymentDTO;
use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\DiscountModel;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PaymentModel;
use App\Models\Services\ServiceModel;
use App\Services\v1\management\billing\invoices\PaymentInvoiceValidator;
use App\Services\v1\network\MikrotikInternetService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

readonly class PaymentService
{
    public function __construct(
        private readonly MikrotikInternetService $mikrotikInternetService,
        private readonly PaymentInvoiceValidator $invoiceValidator,
    )
    {
    }

    /***
     *  Registra pago, activa navegación de ser necesario y actualiza estado financiero.
     *
     * @param PaymentDTO $dto
     * @param array $invoiceIds
     * @param int|null $discountId
     * @return PaymentModel
     * @throws Throwable
     */
    public function createPayment(PaymentDTO $dto, array $invoiceIds, ?int $discountId = null): PaymentModel
    {
        return DB::transaction(function () use ($dto, $invoiceIds, $discountId) {
            //  Calculando descuento sobre el monto de pago
            $discount = $discountId ? DiscountModel::query()->withoutGlobalScopes()->findOrFail($discountId) : null;
            $discountAmount = $discount ? $this->calculateDiscount($discount, $dto->amount) : 0;

            //  El monto efectivo a distribuir es el pago + descuento
            $totalAmountToDistribute = round($dto->amount + $discountAmount, 2);

            /**
             * Valida pertenencia, orden por servicio y monto; devuelve las facturas bloqueadas
             * en orden canónico (el orden en que llegaron del cliente ya no importa).
             */
            $invoices = $this->invoiceValidator->validate(
                clientId: $dto->client_id,
                invoiceIds: $invoiceIds,
                amountToDistribute: $totalAmountToDistribute,
            );

            //  Facturas vencidas previas al pago
            $overdueInvoicesBefore = InvoiceModel::query()
                ->where([
                    ['client_id', $dto->client_id],
                    ['billing_status_id', BillingStatus::OVERDUE->value],
                ])
                ->pluck('id')
                ->toArray();

            //  Crea el pago con los datos del DTO + descuento
            $paymentData = array_merge($dto->toArray(), [
                'discount_id' => $discountId,
                'discount_amount' => $discountAmount,
            ]);
            $payment = PaymentModel::query()->create($paymentData);

            $invoicesUpdated = [];
            $remainingAmount = $totalAmountToDistribute;

            foreach ($invoices as $invoice) {
                //  Si la factura ya está pagada o no hay monto restante, continúa
                if (round($invoice->balance_due, 2) <= 0 || $remainingAmount <= 0) continue;

                //  Calcula cuanto se puede pagar de esa factura
                $maxPayable = round($invoice->balance_due, 2);
                $amountToPay = round(min($remainingAmount, $maxPayable), 2);

                if ($amountToPay <= 0) continue;

                //  Registra el pago aplicado a cada factura
                $payment->invoices()->attach($invoice->id, ['amount_applied' => $amountToPay]);
                $invoice->increment('paid_amount', $amountToPay);

                //  Recalcula balance
                $newBalance = round(max($invoice->total_amount - $invoice->paid_amount, 0), 2);

                //  Estado de la factura
                $newStatus = $newBalance <= 0
                    ? BillingStatus::PAID->value
                    : ($invoice->paid_amount > 0
                        ? BillingStatus::PARTIALLY_PAID->value
                        : $invoice->billing_status_id);
                $invoice->update([
                    'balance_due' => $newBalance,
                    'billing_status_id' => $newStatus,
                ]);

                $remainingAmount = round($remainingAmount - $amountToPay, 2);
                $invoicesUpdated[] = $invoice->id;
            }

            //  Solo actualiza si se pagaron las facturas que están vencidas
            $paidOverdueInvoices = array_intersect($overdueInvoicesBefore, $invoicesUpdated);

            if (!empty($paidOverdueInvoices)) {
                $this->updateInternetAccessForPaidServices($payment->client_id);
            }

            return $payment->load(['invoices', 'discount']);
        });
    }

    /***
     * Retorna el cálculo de descuento
     * @param DiscountModel $discount
     * @param float $base
     * @return float
     */
    private function calculateDiscount(DiscountModel $discount, float $base): float
    {
        if ($discount->percentage !== null && $discount->percentage !== 0.0) {
            return round($base * ($discount->percentage / 100), 2);
        }

        return round(min($discount->amount, $base), 2);
    }

    /***
     * Verifica y actualiza el acceso a internet por cada
     * servicio relacionado con las facturas pagadas
     *
     * @param int $clientId
     * @return void
     */
    public function updateInternetAccessForPaidServices(int $clientId): void
    {
        //  Obteniendo servicios activos del cliente
        $services = ServiceModel::query()
            ->with(['internet', 'node.auth_server'])
            ->where([
                ['client_id', $clientId],
                ['status_id', CommonStatus::ACTIVE->value],
            ])
            ->get();

        foreach ($services as $service) {
            //  Verificar si el servicio tiene facturas asociadas
            $serviceInvoices = $this->getInvoicesForService($service->id, $clientId);

            if ($serviceInvoices->isEmpty()) {
                //  Si el servicio no tiene facturas, activarlo
                $this->activateServiceInternet($service);
                continue;
            }

            //  Verificar si hay facturas vencidas para el servicio
            $hasOverdueInvoices = $serviceInvoices->contains(function ($invoice) {
                return $invoice->billing_status_id === BillingStatus::OVERDUE->value;
            });

            //  Si no hay facturas vencidas para este servicio
            if (!$hasOverdueInvoices) {

                //  Verificar si hay facturas pendientes para este servicio
                $hasPendingInvoices = $serviceInvoices->contains(function ($invoice) {
                    return in_array($invoice->billing_status_id, [
                        BillingStatus::ISSUED->value,
                        BillingStatus::PENDING->value,
                        BillingStatus::PARTIALLY_PAID->value,
                    ]);
                });

                //  Si hay facturas pendientes o si todas están pagadas, activar el servicio
                if ($hasPendingInvoices || $this->areAllInvoicesPaid($serviceInvoices)) {
                    $this->activateServiceInternet($service);
                }
            }
        }
    }

    /***
     * Obtiene las facturas asociadas a un servicio específico
     * @param int $serviceId
     * @param int $clientId
     * @return Collection
     */
    private function getInvoicesForService(int $serviceId, int $clientId): Collection
    {
        //  Obtiene las facturas que contienen items pertenecientes al servicio específico
        return InvoiceModel::query()
            ->where([
                ['client_id', $clientId],
                ['status_id', CommonStatus::ACTIVE->value],
            ])
            ->where('billing_status_id', '!=', BillingStatus::CANCELED->value)
            ->whereHas('items', function ($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->get();
    }

    /***
     * Verifica si todas las facturas del servicio están pagadas
     * @param Collection $invoices
     * @return bool
     */
    private function areAllInvoicesPaid(Collection $invoices): bool
    {
        return $invoices->every(function ($invoice) {
            return $invoice->billing_status_id === BillingStatus::PAID->value;
        });
    }

    /***
     * Activa la navegación de un servicio en Mikrotik
     * @param ServiceModel $service
     * @return void
     */
    private function activateServiceInternet(ServiceModel $service): void
    {
        try {
            //  Verificar que el servicio tenga internet configurado
            if (!$service->internet || !$service->internet->profile) return;

            //  Verificar que tenga un nodo y servidor de autenticación
            if (!$service->node || !$service->node->auth_server) return;

            $authServer = $service->node->auth_server;
            $normalProfile = $service->internet?->profile?->mk_profile;
            $username = $service->internet?->user;

            //  Datos de conexión al auth server
            $server = [
                'ip' => $authServer->ip,
                'user' => $authServer->user,
                'secret' => $authServer->secret,
            ];

            //  Actualizar el usuario en Mikrotik con su perfil de navegación
            $this->mikrotikInternetService->updateUser($server, $username, [
                'profile' => $normalProfile,
                'disabled' => 'no'
            ]);

            Log::channel('reactivation')
                ->info("Servicio de internet activado para el servicio {$service->id}, cliente: {$service->client_id}", [
                    'pppoe_user' => $service->internet?->user ?? null,
                ]);
        } catch (Throwable $e) {
            Log::channel('reactivation')
                ->error("Error al activar la navegación para el servicio {$service->id}: {$e->getMessage()}");
        }
    }

    /***
     * Desactiva la navegación de un servicio específico en Mikrotik
     * @param ServiceModel $service
     * @param string|null $debtProfile
     * @return void
     */
    private function deactivateServiceInternet(ServiceModel $service, ?string $debtProfile = null): void
    {
        try {
            //  Verificar que el servicio tenga internet configurado
            if (!$service->internet) return;

            //  Verificar que tenga un nodo y servidor de autenticación
            if (!$service->node || !$service->node->auth_server) return;

            $authServer = $service->node->auth_server;
            $username = $service->internet?->user;
            $server = [
                'ip' => $authServer->ip,
                'user' => $authServer->user,
                'secret' => $authServer->secret,
            ];

            // Si hay perfil de deuda, cambiar a ese perfil, de lo contrario deshabilitar
            if ($debtProfile && $service->internet?->profile && $service->internet?->profile?->debt_profile) {
                $this->mikrotikInternetService->updateUser($server, $username, [
                    'profile' => $debtProfile,
                    'disabled' => 'no'
                ]);

                Log::channel('cuts')->info("Servicio {$service->id} cambiado a deuda");
            } else {
                $this->mikrotikInternetService->disableUser($server, $username);
                Log::channel('cuts')->info("Servicio {$service->id} deshabilitado por deuda");
            }

        } catch (Throwable $e) {
            Log::channel('cuts')
                ->error("Error al desactivar la navegación para el servicio {$service->id}: " . $e->getMessage());
        }
    }

    /***
     * Retorna el listado de pagos de un cliente.
     * @param int $clientId
     * @return Collection
     */
    public function clientPayments(int $clientId): Collection
    {
        return PaymentModel::query()
            ->with(['user', 'payment_method', 'discount', 'dte.dte_type', 'dte.invalidation'])
            ->where(['client_id' => $clientId])
            ->get();
    }
}
