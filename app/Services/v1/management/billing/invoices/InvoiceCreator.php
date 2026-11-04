<?php

namespace App\Services\v1\management\billing\invoices;

use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use Illuminate\Support\Facades\DB;

class InvoiceCreator
{
    /***
     * @param InvoiceDataCalculator $dataCalculator
     */
    public function __construct(private InvoiceDataCalculator $dataCalculator)
    {

    }

    /***
     *  Crea una factura
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param array $invoiceData
     * @param int $invoiceType
     * @return InvoiceModel
     * @throws \Throwable
     */
    public function create(
        ClientModel $client,
        PeriodModel $period,
        array       $invoiceData,
        int         $invoiceType,
    ): InvoiceModel
    {
        return DB::transaction(function () use ($client, $period, $invoiceData, $invoiceType) {
            $invoice = InvoiceModel::query()
                ->create([
                    'client_id' => $client->id,
                    'billing_period_id' => $period->id,
                    'invoice_type' => $invoiceType,
                    'subtotal' => $invoiceData['total_amount'],
                    'iva' => $invoiceData['total_iva'],
                    'iva_retenido' => $invoiceData['iva_retenido'],
                    'total_amount' => $invoiceData['total_amount'] + $invoiceData['total_iva'] - $invoiceData['iva_retenido'],
                    'paid_amount' => 0,
                    'balance_due' => $invoiceData['total_amount'] + $invoiceData['total_iva'] - $invoiceData['iva_retenido'],
                    'billing_status_id' => BillingStatus::ISSUED->value,
                    'comments' => "Factura generada para el período {$period->name}"
                ]);
            $this->createInvoiceItems($invoice, $client, $invoiceData['items']);
            return $invoice;
        });

    }

    /***
     *  Crea los ítems de la factura
     * @param InvoiceModel $invoice
     * @param ClientModel $client
     * @param array $items
     * @return void
     */
    private function createInvoiceItems(InvoiceModel $invoice, ClientModel $client, array $items): void
    {
        foreach ($items as $item) {
            $ivaRetenido = $this->dataCalculator->calculateIvaRetenido($client, $item['amount']);

            $invoice->items()->create([
                'invoice_id' => $invoice->id,
                'service_id' => $item['service']->id,
                'description' => $item['description'],
                'quantity' => 1,
                'unit_price' => $item['amount'],
                'subtotal' => $item['amount'],
                'iva' => $item['iva'],
                'iva_retenido' => $ivaRetenido,
                'total' => $item['amount'] + $item['iva'] - $ivaRetenido,
                'status_id' => CommonStatus::ACTIVE->value,
            ]);
        }

    }
}
