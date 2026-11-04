<?php

namespace App\Observers\Services;

use App\Models\Services\Logs\InternetServiceLogModel;
use App\Models\Services\ServiceInternetModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class InternetServiceObserver extends Conversion
{
    /**
     * Handle the ServiceInternetModel "created" event.
     */
    public function created(ServiceInternetModel $serviceInternetModel): void
    {
        try {
            DB::transaction(function () use ($serviceInternetModel) {
                InternetServiceLogModel::query()
                    ->create([
                        'internet_service_id' => $serviceInternetModel->id,
                        'service_id' => $serviceInternetModel->service_id,
                        'user_id' => Auth::user()->id ?? 6,
                        'action' => 'create',
                        'before' => null,
                        'after' => $this->convert($serviceInternetModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceInternetModel "updated" event.
     */
    public function updated(ServiceInternetModel $serviceInternetModel): void
    {
        try {
            DB::transaction(function () use ($serviceInternetModel) {
                InternetServiceLogModel::query()
                    ->create([
                        'internet_service_id' => $serviceInternetModel->id,
                        'service_id' => $serviceInternetModel->service_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'update',
                        'before' => $this->convert($serviceInternetModel->getOriginal()),
                        'after' => $this->convert($serviceInternetModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceInternetModel "deleted" event.
     */
    public function deleted(ServiceInternetModel $serviceInternetModel): void
    {
        try {
            DB::transaction(function () use ($serviceInternetModel) {
                InternetServiceLogModel::query()
                    ->create([
                        'internet_service_id' => $serviceInternetModel->id,
                        'service_id' => $serviceInternetModel->service_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'delete',
                        'before' => $this->convert($serviceInternetModel->getOriginal()),
                        'after' => null,
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceInternetModel "restored" event.
     */
    public function restored(ServiceInternetModel $serviceInternetModel): void
    {
        //
    }

    /**
     * Handle the ServiceInternetModel "force deleted" event.
     */
    public function forceDeleted(ServiceInternetModel $serviceInternetModel): void
    {
        //
    }
}
