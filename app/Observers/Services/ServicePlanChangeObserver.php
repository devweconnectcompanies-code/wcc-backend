<?php

namespace App\Observers\Services;

use App\Enums\v1\Billing\ServiceChangeEventTypesEnum;
use App\Jobs\billing\RecalculateInvoiceForServiceChangeJob;
use App\Models\Services\ServicePlanChangeModel;

class ServicePlanChangeObserver
{
    /**
     * Handle the ServicePlanChangeModel "created" event.
     */
    public function created(ServicePlanChangeModel $servicePlanChangeModel): void
    {
        RecalculateInvoiceForServiceChangeJob::dispatch(
            $servicePlanChangeModel->service_id,
            ServiceChangeEventTypesEnum::PLAN_CHANGE,
            ['change_date' => $servicePlanChangeModel->change_date?->toDateString()],
        );
    }

    /**
     * Handle the ServicePlanChangeModel "updated" event.
     */
    public function updated(ServicePlanChangeModel $servicePlanChangeModel): void
    {
        //
    }

    /**
     * Handle the ServicePlanChangeModel "deleted" event.
     */
    public function deleted(ServicePlanChangeModel $servicePlanChangeModel): void
    {
        //
    }

    /**
     * Handle the ServicePlanChangeModel "restored" event.
     */
    public function restored(ServicePlanChangeModel $servicePlanChangeModel): void
    {
        //
    }

    /**
     * Handle the ServicePlanChangeModel "force deleted" event.
     */
    public function forceDeleted(ServicePlanChangeModel $servicePlanChangeModel): void
    {
        //
    }
}
