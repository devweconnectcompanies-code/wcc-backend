<?php

namespace App\Services\v1\management\services;

use App\Enums\v1\Billing\ServiceChangeEventTypesEnum;
use App\Enums\v1\General\CommonStatus;
use App\Models\Services\ServiceModel;
use App\Models\Services\ServicePlanChangeModel;
use App\Models\Services\ServiceUninstallationModel;
use App\Services\v1\management\billing\invoices\InvoiceUpdater;
use Illuminate\Support\Facades\DB;

class ServiceChangeHandler
{
    public function __construct(private InvoiceUpdater $invoiceUpdater)
    {

    }

    /***
     * Se ejecuta cuando hay cambio de plan
     * @param ServiceModel $service
     * @param int $newProfileId
     * @param string $date
     * @return array
     * @throws \Throwable
     */
    public function handlePlanChange(
        ServiceModel $service,
        int          $newProfileId,
        string       $date
    ): array
    {
        return DB::transaction(function () use ($service, $newProfileId, $date) {
            $change = ServicePlanChangeModel::query()
                ->create([
                    'service_id' => $service->id,
                    'old_profile_id' => $service->internet->internet_profile_id,
                    'new_profile_id' => $newProfileId,
                    'change_date' => $date,
                ]);

            $service->internet->update([
                'internet_profile_id' => $newProfileId,
            ]);

            $invoices = $this->invoiceUpdater->updateInvoicesForServiceChange(
                $service,
                ServiceChangeEventTypesEnum::PLAN_CHANGE->value,
                ['change_date' => $date]
            );

            return [
                'change' => $change,
                'invoice_results' => $invoices,
            ];
        });
    }

    /***
     * Actualiza facturas cuando hay desinstalación
     * @param ServiceModel $service
     * @param string $date
     * @return array
     * @throws \Throwable
     */
    public function handleUninstallation(ServiceModel $service, string $date): array
    {
        return DB::transaction(function () use ($service, $date) {
            $uninstall = ServiceUninstallationModel::query()
                ->create([
                    'service_id' => $service->id,
                    'uninstallation_date' => $date,
                ]);

            $service->update(['status_id' => CommonStatus::INACTIVE->value]);

            $invoices = $this->invoiceUpdater->updateInvoicesForServiceChange(
                $service,
                ServiceChangeEventTypesEnum::UNINSTALLATION->value,
                ['uninstallation_date' => $date]
            );

            return [
                'uninstall' => $uninstall,
                'invoice_results' => $invoices,
            ];
        });
    }
}
