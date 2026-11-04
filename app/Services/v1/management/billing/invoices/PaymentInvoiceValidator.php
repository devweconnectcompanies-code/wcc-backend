<?php

namespace App\Services\v1\management\billing\invoices;

use App\Models\Billing\InvoiceModel;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PaymentInvoiceValidator
{
    /**
     * Valida y bloquea las facturas que se van a pagar.
     * DEBE llamarse dentro de una transacción (usa lockForUpdate).
     *
     * @param int $clientId
     * @param array $invoiceIds
     * @param float $amountToDistribute
     * @return Collection
     * @throws ValidationException
     */
    public function validate(int $clientId, array $invoiceIds, float $amountToDistribute): Collection
    {
        $ids = array_values(array_unique(array_map('intval', $invoiceIds)));

        if (empty($ids)) {
            throw ValidationException::withMessages([
                'invoices' => 'Debes seleccionar al menos una factura.'
            ]);
        }

        //  Todas las facturas pagables del cliente, ya ordenadas y bloqueadas
        $payable = InvoiceGrouping::payableQuery($clientId)->lockForUpdate()->get();

        //  1. Pertenencia: todas deben ser del cliente y estar pendientes de pago
        $invalid = array_diff($ids, $payable->pluck('id')->all());
        if (!empty($invalid)) {
            throw ValidationException::withMessages([
                'invoices' => 'Las facturas ' . implode(', ', $invalid) . ' no pertenecen al cliente o no están pendientes de pago.'
            ]);
        }

        //  2. Orden estricto por grupo: lo seleccionado debe ser un prefijo de la cola de cada grupo.
        foreach ($payable->groupBy(fn(InvoiceModel $inv) => InvoiceGrouping::groupKey($inv)) as $groupInvoices) {
            $gapFound = false;

            foreach ($groupInvoices as $invoice) {
                if (!in_array($invoice->id, $ids, true)) {
                    $gapFound = true;
                    continue;
                }

                if ($gapFound) {
                    throw ValidationException::withMessages([
                        'invoices' => "La factura {$invoice->id} ({$invoice->period?->name}) no se puede pagar sin pagar antes las facturas anteriores del mismo servicio."
                    ]);
                }
            }
        }

        //  3. El monto a distribuir debe coincidir con el saldo de lo seleccionado
        $selected = $payable->whereIn('id', $ids)->values();
        $expected = round($selected->sum(fn(InvoiceModel $inv) => (float)$inv->balance_due), 2);

        if (abs($expected - round($amountToDistribute, 2)) > 0.009) {
            throw ValidationException::withMessages([
                'amount' => 'El monto no coincide con el saldo de las facturas seleccionadas (esperado: $' . number_format($expected, 2) . ')',
            ]);
        }

        return $selected;
    }
}
