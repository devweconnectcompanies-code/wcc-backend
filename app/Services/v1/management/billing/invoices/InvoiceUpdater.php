<?php

namespace App\Services\v1\management\billing\invoices;

use App\Enums\v1\Billing\ServiceChangeEventTypesEnum;
use App\Enums\v1\General\BillingStatus;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceUpdater
{
    /***
     * @param InvoiceValidator $invoiceValidator
     * @param InvoiceDataCalculator $dataCalculator
     * @param InvoiceCreator $invoiceCreator
     * @param ItemCalculator $itemCalculator
     */
    public function __construct(
        private InvoiceValidator      $invoiceValidator,
        private InvoiceDataCalculator $dataCalculator,
        private InvoiceCreator        $invoiceCreator,
        private ItemCalculator        $itemCalculator,
    )
    {
    }

    /***
     * Ejecuta actualización de datos en facturas cuando hay cambios en el servicio
     * @param ServiceModel $service
     * @param ServiceChangeEventTypesEnum $changeType
     * @param array $changeData
     * @return array
     */
    public function updateInvoicesForServiceChange(
        ServiceModel                $service,
        ServiceChangeEventTypesEnum $changeType,
        array                       $changeData = []
    ): array
    {
        $results = [
            'updated_invoices' => 0,
            'recreated_invoices' => [],
            'errors' => [],
        ];

        try {
            DB::transaction(function () use ($service, $changeType, $changeData, &$results) {
                $this->processServiceChange($service, $changeType, $changeData, $results);
            });
        } catch (\Throwable $e) {
            Log::channel('invoices')
                ->error("Error actualizando facturas", [
                    'service_id' => $service->id,
                    'error' => $e->getMessage(),
                ]);

            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    /***
     * Obtiene el cliente asociado al servicio y determina los periodos afectados por el cambio
     * @param ServiceModel $service
     * @param ServiceChangeEventTypesEnum $changeType
     * @param array $changeData
     * @param array $results
     * @return void
     * @throws \Throwable
     */
    private function processServiceChange(
        ServiceModel                $service,
        ServiceChangeEventTypesEnum $changeType,
        array                       $changeData,
        array                       &$results
    ): void
    {
        $client = $service->client;
        $periods = $this->getAffectedPeriods($changeType, $changeData);

        foreach ($periods as $period) {
            if ($period->is_closed) {
                continue;
            }

            $this->updatePeriodInvoices($client, $service, $period, $changeType, $changeData, $results);
        }
    }

    /***
     *  Según el tipo de cambio obtiene los periodos que se ven afectados.
     * @param ServiceChangeEventTypesEnum $changeType
     * @param array $changeData
     * @return array
     */
    private function getAffectedPeriods(
        ServiceChangeEventTypesEnum $changeType,
        array                       $changeData,
    ): array
    {
        return match ($changeType) {
            ServiceChangeEventTypesEnum::PLAN_CHANGE => $this->getPeriodsFromDate(
                $changeData['change_date'] ?? Carbon::now()
            ),
            ServiceChangeEventTypesEnum::UNINSTALLATION => $this->getPeriodsFromDate(
                $changeData['uninstallation_date'] ?? Carbon::now()
            ),
            ServiceChangeEventTypesEnum::REACTIVATION => $this->getPeriodsFromDate(
                $changeData['reactivation_date'] ?? Carbon::now()
            ),
            default => []
        };
    }

    /***
     * Busca periodos cuyo inicio sea antes a la fecha y cuyo fin sea después o igual a la fecha.
     * @param string $date
     * @return array
     */
    private function getPeriodsFromDate(string $date): array
    {
        $date = Carbon::parse($date);

        return PeriodModel::query()
            ->where('period_start', '<=', $date)
            ->where('period_end', '>=', $date)
            ->get()
            ->all();
    }

    /***
     * Busca la factura correspondiente al cliente, periodo y servicio
     * @param ClientModel $client
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @param ServiceChangeEventTypesEnum $changeType
     * @param array $changeData
     * @param array $results
     * @return void
     * @throws \Throwable
     */
    private function updatePeriodInvoices(
        ClientModel                 $client,
        ServiceModel                $service,
        PeriodModel                 $period,
        ServiceChangeEventTypesEnum $changeType,
        array                       $changeData,
        array                       &$results
    ): void
    {
        $invoice = $this->findInvoiceForService($client, $period, $service);

//        if (!$invoice) {
//            $invoice = $this->invoiceCreator->create($client, $period, collect([$service]));
//
//            $results['created_invoices'][] = $invoice->id;
//            return;
//        }

//        if ($invoice->billing_status_id === BillingStatus::PAID->value) {
//            $this->createCreditMemoForChange($invoice, $service, $period, $results);
//            return;
//        }

        $this->recalculateInvoice($invoice, $period);
        $results['updated_invoices']++;
    }

    /***
     * Obtiene los servicios únicos de los ítems de cada factura, calcula nuevamente los datos de los ítems,
     * elimina los anteriores y crea los nuevos con los nuevos valores.
     * @param InvoiceModel $invoice
     * @param PeriodModel $period
     * @return void
     */
    private function recalculateInvoice(InvoiceModel $invoice, PeriodModel $period): void
    {
        $services = $invoice->items->map(fn($i) => $i->service)->filter()->unique('id');
        $data = $this->dataCalculator->calculateForClient($invoice->client, $period, $services);
        $invoice->items()->delete();
        $this->createInvoiceItems($invoice, $data['items']);
        $total = $data['total_amount'] + $data['total_iva'] - $data['iva_retenido'];

        $invoice->update([
            'subtotal' => $data['total_amount'],
            'iva' => $data['total_iva'],
            'iva_retenido' => $data['iva_retenido'],
            'total_amount' => $total,
            'balance_due' => $total - $invoice->paid_amount,
        ]);
    }

    /***
     * Guarda los items de cada factura
     * @param InvoiceModel $invoice
     * @param array $items
     * @return void
     */
    private function createInvoiceItems(InvoiceModel $invoice, array $items): void
    {
        foreach ($items as $item) {
            $invoice->items()->create([
                'service_id' => $item['service']->id,
                'description' => $item['description'],
                'quantity' => 1,
                'unit_price' => $item['amount'],
                'iva' => $item['iva'],
                'iva_retenido' => $item['iva_retenido'] ?? 0,
                'total' => $item['amount'] + $item['iva'] - ($item['iva_retenido'] ?? 0),
            ]);
        }
    }

    /***
     * Busca la factura correspondiente del cliente, periodo y que tenga items
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param ServiceModel $service
     * @return InvoiceModel|null
     */
    private function findInvoiceForService(
        ClientModel  $client,
        PeriodModel  $period,
        ServiceModel $service
    ): ?InvoiceModel
    {
        return InvoiceModel::query()
            ->where([
                ['client_id', $client->id],
                ['billing_period_id', $period->id],
            ])
            ->whereHas('items', fn($q) => $q->where('service_id', $service->id))
            ->first();
    }

    /***
     * Calcula el total anterior de los items de la factura, calcula los nuevos, obtiene la diferencia entre el total
     * nuevo y el anterior. Si la diferencia es mayor crea nota de débito.
     * @param InvoiceModel $invoice
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @param array $results
     * @return void
     */
    private function createCreditMemoForChange(
        InvoiceModel $invoice,
        ServiceModel $service,
        PeriodModel  $period,
        array        &$results
    ): void
    {
        $old = $invoice->items()
            ->where('service_id', $service->id)
            ->sum('total');
        $newItems = $this->itemCalculator->calculateForService($service, $period);
        $new = collect($newItems)->sum(fn($i) => $i['amount'] + $i['iva']);
        $diff = $new - $old;

        if (abs($diff) < 0.01) {
            return;
        }

        $memo = InvoiceModel::query()
            ->create([
                'client_id' => $invoice->client_id,
                'billing_period_id' => $period->id,
                'invoice_type' => $diff < 0 ? 3 : 4,
                'total_amount' => abs($diff),
                'balance_due' => abs($diff),
                'billing_status_id' => BillingStatus::ISSUED->value,
//                'parent_invoice_id' => $invoice->id
            ]);

        $results['recreated_invoices'][] = $memo->id;
    }

    /**
     * Recalcula una factura puntual a partir de su ID, usando su propio período.
     *
     * @param int $invoiceId
     * @return InvoiceModel
     * @throws \Throwable
     */
    public function recalculateInvoiceById(int $invoiceId): InvoiceModel
    {
        $invoice = InvoiceModel::query()
            ->with(['items.service', 'period', 'client'])
            ->findOrFail($invoiceId);

        return DB::transaction(function () use ($invoice) {
            $this->recalculateInvoice(invoice: $invoice, period: $invoice->period);
            return $invoice->fresh(['items', 'period', 'client']);
        });
    }
}
