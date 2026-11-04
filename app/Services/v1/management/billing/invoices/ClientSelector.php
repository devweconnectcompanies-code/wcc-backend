<?php

namespace App\Services\v1\management\billing\invoices;

use App\Enums\v1\Clients\ClientTypes;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ClientSelector
{
    /***
     * Obtiene los clientes facturables
     * @param PeriodModel $period
     * @param bool $allClients
     * @return Collection
     */
    public function getBillableClients(PeriodModel $period, bool $allClients = false): Collection
    {
        $query = ClientModel::query()
            ->with([
                'services' => fn($q) => $q->activeOrUninstalledInPeriod($period)->with('internet.profile'),
                'client_type',
                'corporate_info',
            ])
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereNot('client_type_id', ClientTypes::FREE->value);

        if (!$allClients) {
            $query->whereHas('services', fn($q) => $q->activeOrUninstalledInPeriod($period)
                ->where(function ($q) use ($period) {
                    $q->where('installation_date', '<=', $period->period_end)
                        ->orWhereNull('installation_date');
                })
            );
        }

        return $query->get();
    }

    /***
     * Retorna la query builder para clientes facturables (usado con chunkById)
     * @param PeriodModel $period
     * @param bool $allClients
     * @return Builder
     */
    public function getBillableClientsQuery(PeriodModel $period, bool $allClients = false): Builder
    {
        $query = ClientModel::query()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereNot('client_type_id', ClientTypes::FREE->value);

        if (!$allClients) {
            $query->whereHas('services', fn($q) => $q->activeOrUninstalledInPeriod($period)
                ->where(function ($q) use ($period) {
                    $q->where('installation_date', '<=', $period->period_end)
                        ->orWhereNull('installation_date');
                })
            );
        }

        return $query;
    }
}
