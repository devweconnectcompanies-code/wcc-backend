<?php

namespace App\Services\v1\management\billing\otherInvoices;

use App\Enums\v1\Accounting\TaxRate;
use App\Models\Billing\OtherInvoiceDetailModel;
use App\Models\Billing\OtherInvoiceModel;
use App\Models\Clients\ClientModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class OtherInvoiceService
{

    /***
     * Crea una factura a partir de datos manuales.
     *
     * @param int $type
     * @param array $data
     * @param int $userId
     * @return OtherInvoiceModel
     * @throws Throwable
     */

    public function createFromManualData(int $type, array $data, int $userId): OtherInvoiceModel
    {
        $client = ClientModel::query()
            ->with('corporate_info')
            ->findOrFail($data['client_id']);

        $hasRetainedIva = $client->corporate_info?->retained_iva ?? false;

        [$neto, $iva, $ivaRetenido, $total] = $this->calculateAmounts(
            amount: (float)$data['totals']['total'],
            retainedIva: $hasRetainedIva,
        );


        return DB::transaction(function () use ($type, $data, $userId, $iva, $ivaRetenido, $total, $neto) {
            if (empty($data['payment_method'])) $data['payment_method'] = 1;

            $otherInvoice = OtherInvoiceModel::create([
                'document_type_id' => $type,
                'client_id' => $data['client_id'],
                'payment_condition' => (int)$data['payment_condition'],
                'payment_method_id' => (int)$data['payment_method'],
                'subtotal' => (float)$data['totals']['total'] ?? 0,
                'neto' => $neto,
                'iva' => $iva ?? 0,
                'iva_retenido' => $ivaRetenido ?? 0,
                'discount_amount' => (float)$data['totals']['discount'] ?? 0,
                'total_amount' => $total ?? 0,
                'issue_date' => Carbon::now(),
                'created_by' => $userId,
                'status_id' => true,
            ]);

            foreach ($data['items'] as $item) {
                $unitPrice = (float)$item['unit_price'] / TaxRate::VALOR_NETO->value();
                $subtotal = (float)$item['unit_price'] * (int)$item['quantity'];
                $lineNeto = $unitPrice * (int)$item['quantity'];
                $lineIVA = $lineNeto * TaxRate::IVA->value();

                OtherInvoiceDetailModel::create([
                    'other_invoice_id' => $otherInvoice->id,
                    'description' => $item['description'],
                    'item_type' => (int)$item['item_type'],
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineNeto,
                    'iva' => $lineIVA,
                    'total' => $lineNeto + $lineIVA,
                ]);
            }

            return $otherInvoice;
        });
    }

    /***
     * Lógica financiera
     *
     * @param float $amount
     * @param bool $retainedIva
     * @return array
     */
    private function calculateAmounts(float $amount, bool $retainedIva): array
    {
        $neto = $amount / TaxRate::VALOR_NETO->value();
        $iva = $neto * TaxRate::IVA->value();
        $ivaRetenido = $retainedIva ? $neto * TaxRate::IVA_RETENIDO->value() : 0;
        $total = $neto + $iva - $ivaRetenido;

        return [$neto, $iva, $ivaRetenido, $total];
    }
}
