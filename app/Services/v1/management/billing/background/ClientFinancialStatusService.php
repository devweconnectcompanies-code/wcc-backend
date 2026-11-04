<?php

namespace App\Services\v1\management\billing\background;

use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\ClientFinancialStatusModel;
use App\Models\Billing\InvoiceModel;
use App\Models\Clients\ClientModel;

class ClientFinancialStatusService
{
    public function __construct(private InvoiceStatusService $invoiceStatusService)
    {

    }

    /***
     * Crea o actualiza el estado financiero de un cliente
     * Actualiza los estados de las facturas según período
     * @param int $clientId
     * @param bool $updateInvoiceStatuses
     * @return void
     */
    public function updateClientFinancialStatus(int $clientId, bool $updateInvoiceStatuses = true): void
    {
        $client = ClientModel::query()->findOrFail($clientId);
        $invoices = InvoiceModel::query()
            ->with('period')
            ->where('client_id', $clientId)
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->where('billing_status_id', '!=', BillingStatus::CANCELED->value)
            ->get();

        if ($updateInvoiceStatuses) {
            foreach ($invoices as $invoice) {
                $this->invoiceStatusService->updateInvoiceStatusByPeriod($invoice);
            }
            $invoices = $invoices->fresh();
        }

        $currentBalance = $invoices->sum('balance_due');
        $totalPaidAmount = $invoices->sum('paid_amount');
        $totalInvoices = $invoices->count();
        $paidInvoices = $invoices->where('billing_status_id', BillingStatus::PAID->value)->count();
        $pendingInvoices = $invoices->whereIn('billing_status_id', [
            BillingStatus::ISSUED->value,
            BillingStatus::PENDING->value,
        ])->count();
        $overdueInvoices = $invoices->where('billing_status_id', BillingStatus::OVERDUE->value);
        $overdueInvoicesCount = $overdueInvoices->count();
        $overdueBalance = $overdueInvoices->sum('balance_due');

        $statusId = $this->determineFinancialStatus($overdueBalance, $currentBalance);

        ClientFinancialStatusModel::query()->updateOrCreate(
            ['client_id' => $clientId],
            [
                'current_balance' => $currentBalance,
                'overdue_balance' => $overdueBalance,
                'total_paid_amount' => $totalPaidAmount,
                'total_invoices' => $totalInvoices,
                'paid_invoices' => $paidInvoices,
                'pending_invoices' => $pendingInvoices,
                'overdue_invoices' => $overdueInvoicesCount,
                'status_id' => $statusId
            ]
        );
    }

    /***
     * Determina el estado financiero basado en la deuda
     * @param float $overdueBalance
     * @param float $currentBalance
     * @return int
     */
    private function determineFinancialStatus(float $overdueBalance, float $currentBalance): int
    {
        if ($overdueBalance == 0 && $currentBalance == 0) return BillingStatus::PAID->value;
        if ($overdueBalance > 0) return BillingStatus::OVERDUE->value;
        if ($currentBalance > 0) return BillingStatus::PENDING->value;
        return BillingStatus::PAID->value;
    }

    /***
     * Actualiza estados financieros de múltiples clientes
     * @param array $clientIds
     * @param bool $updateInvoiceStatuses
     * @return array
     */
    public function updateMultipleClients(array $clientIds, bool $updateInvoiceStatuses = true): array
    {
        $results = [
            'updated' => 0,
            'total' => count($clientIds),
            'errors' => [],
        ];

        foreach ($clientIds as $clientId) {
            try {
                $this->updateClientFinancialStatus($clientId, $updateInvoiceStatuses);
                $results['updated']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Cliente {$clientId}: {$e->getMessage()}";
            }
        }

        return $results;
    }

    /***
     * Actualiza todos los clientes con facturas
     * @param bool $updateInvoiceStatuses
     * @return array
     */
    public function updateAllClientsWithInvoices(bool $updateInvoiceStatuses = true): array
    {
        $clientsIds = InvoiceModel::query()
            ->distinct('client_id')
            ->pluck('client_id');

        $updated = 0;
        $errors = [];

        foreach ($clientsIds as $clientId) {
            try {
                $this->updateClientFinancialStatus($clientId, $updateInvoiceStatuses);
                $updated++;
            } catch (\Exception $e) {
                $errors[] = "Cliente {$clientId}: {$e->getMessage()}";
            }
        }

        return [
            'updated' => $updated,
            'total' => $clientsIds->count(),
            'errors' => $errors
        ];
    }

    /***
     * Actualiza solo los estados de facturas de un cliente sin recalcular métricas
     * @param int $clientId
     * @return array
     */
    public function updateClientInvoiceStatuses(int $clientId): array
    {
        $invoices = InvoiceModel::query()
            ->with('period')
            ->where('client_id', $clientId)
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereIn('billing_status_id', [
                BillingStatus::ISSUED->value,
                BillingStatus::PENDING->value,
                BillingStatus::OVERDUE->value
            ])
            ->get();

        $results = [
            'updated' => 0,
            'total' => $invoices->count(),
            'errors' => [],
        ];

        foreach ($invoices as $invoice) {
            try {
                if ($this->invoiceStatusService->updateInvoiceStatusByPeriod($invoice)) {
                    $results['updated']++;
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Factura {$invoice->id}: {$e->getMessage()}";
            }
        }

        return $results;
    }
}
