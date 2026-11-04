<?php

namespace App\Services\v1\management\billing\invoices;

use App\Models\Billing\PeriodModel;
use App\Models\Services\ServiceModel;
use App\Models\Services\ServicePlanChangeModel;
use App\Models\Services\ServiceUninstallationModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ItemCalculator
{
    /***
     * Calcula los ítems de facturación para un servicio
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @return array
     */
    public function calculateForService(ServiceModel $service, PeriodModel $period): array
    {
        $periodStart = Carbon::parse($period->period_start);
        $periodEnd = Carbon::parse($period->period_end);
        $planChanges = $this->getPlanChanges($service->id, $periodStart, $periodEnd);

        if ($planChanges->isNotEmpty()) {
            return $this->calculateWithPlanChanges($service, $period, $planChanges);
        }

        return $this->calculateSingleItem($service, $period);
    }

    /***
     * Calcula ítem cuando no hay cambio de plan
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @return array
     */
    private function calculateSingleItem(ServiceModel $service, PeriodModel $period): array
    {
        $amount = $this->calculateServiceAmount($service, $period);
        if ($amount <= 0) {
            return [];
        }

        $profile = $service->internet->profile ?? null;
        $netValue = $profile ? (float)$profile->net_value : 0;
        $iva = $this->calculateIva($netValue, $amount);

        return [
            [
                'service' => $service,
                'amount' => $amount,
                'net_value' => $netValue,
                'iva' => $iva,
                'description' => $this->getServiceDescription($service, $period),
            ]
        ];
    }

    /***
     * Calcula ítems cuando hay cambio de plan
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @param Collection $planChanges
     * @return array
     */
    private function calculateWithPlanChanges(
        ServiceModel $service,
        PeriodModel  $period,
        Collection   $planChanges
    ): array
    {
        $items = [];

        foreach ($this->splitPeriodByPlanChanges($service, $period, $planChanges) as $segment) {
            $item = $this->createPlanChangeItem($service, $segment['profile'], $segment['start'], $segment['end']);

            if ($item) $items[] = $item;
        }
        return $items;
    }

    /***
     * Divide periodos según cambio de plan
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @param Collection $planChanges
     * @return array
     */
    private function splitPeriodByPlanChanges(
        ServiceModel $service,
        PeriodModel  $period,
        Collection   $planChanges
    ): array
    {
        $segments = [];
        $periodStart = Carbon::parse($period->period_start);
        $periodEnd = Carbon::parse($period->period_end);
        $currentDate = $periodStart->copy();
        $firstChange = $planChanges->first();
        $currentProfile = $firstChange->old_internet_profile ?? $service->internet->profile;

        foreach ($planChanges as $change) {
            $changeDate = Carbon::parse($change->change_date);

            if ($currentDate->lt($changeDate)) {
                $endDate = $changeDate->copy()->subDay();

                if ($endDate->gt($periodEnd)) {
                    $endDate = $periodEnd;
                }

                if ($currentDate->lte($endDate)) {
                    $segments[] = [
                        'profile' => $currentProfile,
                        'start' => $currentDate->copy(),
                        'end' => $endDate,
                    ];
                }
            }
            $currentDate = $changeDate;
            $currentProfile = $change->new_internet_profile;
        }

        if ($currentDate->lte($periodEnd)) {
            $segments[] = [
                'profile' => $currentProfile,
                'start' => $currentDate,
                'end' => $periodEnd,
            ];
        }

        return $segments;
    }

    /***
     * Crea un ítem para un segmento con cambio de plan
     * @param ServiceModel $service
     * @param $profile
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array|null
     */
    private function createPlanChangeItem(
        ServiceModel $service,
                     $profile,
        Carbon       $startDate,
        Carbon       $endDate,
    ): ?array
    {
        $netValue = $profile ? (float)$profile->net_value : 0;
        $amount = $this->calculateProportionalAmount($startDate, $endDate, $netValue);
        $days = $startDate->diffInDays($endDate) + 1;

        if ($amount <= 0) return null;

        return [
            'service' => $service,
            'amount' => $amount,
            'net_value' => $netValue,
            'iva' => $this->calculateIva($netValue, $amount),
            'description' => $this->getPlanChangeDescription($profile, $startDate, $endDate, $days),
        ];
    }

    /***
     * Obtiene los cambios de plan durante un período
     * @param int $serviceId
     * @param Carbon $start
     * @param Carbon $end
     * @return Collection
     */
    private function getPlanChanges(int $serviceId, Carbon $start, Carbon $end): Collection
    {
        return ServicePlanChangeModel::query()
            ->with(['old_internet_profile', 'new_internet_profile'])
            ->where('service_id', $serviceId)
            ->whereBetween('change_date', [$start, $end])
            ->orderBy('change_date')
            ->get();
    }

    /***
     * Calcula monto proporcional
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param float $monthlyPrice
     * @return float
     */
    public function calculateProportionalAmount(Carbon $startDate, Carbon $endDate, float $monthlyPrice): float
    {
        if ($monthlyPrice <= 0) return 0;
        $daysInMonth = $startDate->copy()->startOfMonth()->daysInMonth();
        $daysActive = $startDate->diffInDays($endDate) + 1;

        return round(($monthlyPrice / $daysInMonth) * $daysActive, 8);
    }

    /***
     * Calcula el monto total del servicio
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @return float
     */
    public function calculateServiceAmount(ServiceModel $service, PeriodModel $period): float
    {
        $profile = $service->internet->profile ?? null;
        if (!$profile || $profile->net_value <= 0) return 0;

        $monthlyPrice = (float)$profile->net_value;
        $periodStart = Carbon::parse($period->period_start);
        $periodEnd = Carbon::parse($period->period_end);
        $uninstallationDate = $this->getUninstallationDate($service, $period);

        if ($service->installation_date) {
            $installationDate = Carbon::parse($service->installation_date);

            if ($installationDate->gt($periodStart) && $installationDate->lte($periodEnd)) {
                return $this->calculateProportionalAmount(
                    $installationDate,
                    $uninstallationDate ?? $periodEnd,
                    $monthlyPrice
                );
            }
        }

        if ($uninstallationDate) {
            return $this->calculateProportionalAmount($periodStart, $uninstallationDate, $monthlyPrice);
        }

        $planChanges = $this->getPlanChanges($service->id, $periodStart, $periodEnd);

        if ($planChanges->isNotEmpty()) {
            return $this->calculateAmountWithPlanChanges($service, $period, $planChanges);
        }

        return $monthlyPrice;
    }

    /***
     * Calcula monto total cuando hay cambios de plan
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @param Collection $planChanges
     * @return float
     */
    private function calculateAmountWithPlanChanges(
        ServiceModel $service,
        PeriodModel  $period,
        Collection   $planChanges
    ): float
    {
        $total = 0;

        foreach ($this->splitPeriodByPlanChanges($service, $period, $planChanges) as $segment) {
            $price = $segment['profile'] ? (float)$segment['profile']->net_value : 0;
            $total += $this->calculateProportionalAmount($segment['start'], $segment['end'], $price);
        }

        return $total;
    }

    /***
     * Obtiene fecha de desinstalación
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @return Carbon|null
     */
    public function getUninstallationDate(ServiceModel $service, PeriodModel $period): ?Carbon
    {
        $periodStart = Carbon::parse($period->period_start);
        $periodEnd = Carbon::parse($period->period_end);
        $uninstallation = ServiceUninstallationModel::query()
            ->where('service_id', $service->id)
            ->whereBetween('uninstallation_date', [$periodStart, $periodEnd])
            ->first();

        return $uninstallation ? Carbon::parse($uninstallation->uninstallation_date) : null;
    }

    /***
     * Calcula el IVA
     * @param float $netValue
     * @param float $amount
     * @return float
     */
    public function calculateIva(float $netValue, float $amount): float
    {
        if ($netValue <= 0) return 0;
        return round($amount * 0.13, 8);
    }

    /***
     * Descripción para un servicio sin cambios
     * @param ServiceModel $service
     * @param PeriodModel $period
     * @return string
     */
    public function getServiceDescription(ServiceModel $service, PeriodModel $period): string
    {
        $profile = $service->internet->profile ?? null;
        $profileName = $profile ? $profile->name : 'Servicio de internet';
        $periodStart = Carbon::parse($period->period_start);
        $periodEnd = Carbon::parse($period->period_end);
        $uninstallationDate = $this->getUninstallationDate($service, $period);

        if ($service->installation_date) {
            $installationDate = Carbon::parse($service->installation_date);

            if ($installationDate->gt($periodStart) && $installationDate->lte($periodEnd)) {
                if ($uninstallationDate) {
                    $days = $installationDate->diffInDays($uninstallationDate) + 1;
                    return "$profileName ($days días - del {$installationDate->format('d/m/Y')} al {$uninstallationDate->format('d/m/Y')})";
                }

                $days = $installationDate->diffInDays($periodEnd) + 1;
                return "$profileName ($days días de consumo - desde {$installationDate->format('d/m/Y')})";
            }
        }

        if ($uninstallationDate) {
            $days = $periodStart->diffInDays($uninstallationDate) + 1;
            return "$profileName ($days días - del {$periodStart->format('d/m/Y')} hasta {$uninstallationDate->format('d/m/Y')})";
        }

        return $profileName;
    }

    /***
     * Descripción para ítems con cambios de plan
     * @param $profile
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $days
     * @return string
     */
    public function getPlanChangeDescription($profile, Carbon $startDate, Carbon $endDate, int $days): string
    {
        $profileName = $profile ? $profile->name : 'Servicio de internet';

        return sprintf(
            '%s (%d días - del %s al %s)',
            $profileName,
            $days,
            $startDate->format('d/m/Y'),
            $endDate->format('d/m/Y')
        );
    }
}
