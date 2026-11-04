<?php

namespace App\Observers\Services;

use App\Enums\v1\Billing\ServiceChangeEventTypesEnum;
use App\Jobs\billing\RecalculateInvoiceForServiceChangeJob;
use App\Models\Services\ServiceUninstallationModel;

class ServiceUninstallationObserver
{
    /**
     * Handle the ServiceUninstallationModel "created" event.
     */
    public function created(ServiceUninstallationModel $serviceUninstallationModel): void
    {
        RecalculateInvoiceForServiceChangeJob::dispatch(
            $serviceUninstallationModel->service_id,
            ServiceChangeEventTypesEnum::UNINSTALLATION,
            ['uninstallation_date' => $serviceUninstallationModel->uninstallation_date?->toDateString()],
        );
    }

    /**
     * Handle the ServiceUninstallationModel "updated" event.
     */
    public function updated(ServiceUninstallationModel $serviceUninstallationModel): void
    {
        //
    }

    /**
     * Handle the ServiceUninstallationModel "deleted" event.
     */
    public function deleted(ServiceUninstallationModel $serviceUninstallationModel): void
    {
        //
    }

    /**
     * Handle the ServiceUninstallationModel "restored" event.
     */
    public function restored(ServiceUninstallationModel $serviceUninstallationModel): void
    {
        //
    }

    /**
     * Handle the ServiceUninstallationModel "force deleted" event.
     */
    public function forceDeleted(ServiceUninstallationModel $serviceUninstallationModel): void
    {
        //
    }
}
