<?php

namespace App\Services\v1\management\accounting;

use App\Enums\v1\Billing\DocumentTypes;
use App\Enums\v1\Clients\ClientTypes;
use App\Models\Billing\InvoiceModel;
use Illuminate\Support\Collection;

class AccountingService
{
    /****
     * Retorna las facturas pertenecientes al cliente durante el año en curso, cumpliendo estas condiciones:
     * - Debe ser credito fiscal.
     * - No debe tener DTE generados activos.
     *
     * @param int $clientId
     * @param int $year
     * @return Collection
     */
    public function clientInvoices(int $clientId, int $year): Collection
    {
        return InvoiceModel::query()
            ->where('client_id', $clientId)
            ->whereHas('period', function ($q) use ($year) {
                $q->whereYear('period_start', $year);
            })
            ->whereHas('client', function ($query) {
                $query->where(function ($q) {
                    $q->where('client_type_id', ClientTypes::CORPORATE->value)
                        ->orWhere('document_type_id', DocumentTypes::CREDITO_FISCAL->value);
                });
            })
            ->whereDoesntHave('dtes', function ($q) {
                $q->where('status_id', true);
            })
            ->with(['period', 'items'])
            ->get();
    }
}
