<?php

namespace App\Observers\Services;

use App\Models\Services\Logs\ServiceLogModel;
use App\Models\Services\ServiceModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ServiceObserver extends Conversion
{
    /**
     * Handle the ServiceModel "created" event.
     * @param ServiceModel $serviceModel
     * @return void
     */
    public function created(ServiceModel $serviceModel): void
    {
        try {
            DB::transaction(function () use ($serviceModel) {
                ServiceLogModel::query()
                    ->create([
                        'service_id' => $serviceModel->id,
                        'client_id' => $serviceModel->client_id,
                        'user_id' => Auth::user()->id ?? 6,
                        'action' => 'create',
                        'before' => null,
                        'after' => $this->convert($serviceModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceModel "updated" event.
     * @param ServiceModel $serviceModel
     * @return void
     */
    public function updated(ServiceModel $serviceModel): void
    {
        try {
            DB::transaction(function () use ($serviceModel) {
                ServiceLogModel::query()
                    ->create([
                        'service_id' => $serviceModel->id,
                        'client_id' => $serviceModel->client_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'update',
                        'before' => $this->convert($serviceModel->getOriginal()),
                        'after' => $this->convert($serviceModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceModel "deleted" event.
     * @param ServiceModel $serviceModel
     * @return void
     */
    public function deleted(ServiceModel $serviceModel): void
    {
        try {
            DB::transaction(function () use ($serviceModel) {
                ServiceLogModel::query()
                    ->create([
                        'service_id' => $serviceModel->id,
                        'client_id' => $serviceModel->client_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'delete',
                        'before' => $this->convert($serviceModel->getOriginal()),
                        'after' => null,
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceModel "restored" event.
     */
    public function restored(ServiceModel $serviceModel): void
    {
        //
    }

    /**
     * Handle the ServiceModel "force deleted" event.
     */
    public function forceDeleted(ServiceModel $serviceModel): void
    {
        //
    }
}
