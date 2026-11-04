<?php

namespace App\Observers\Services;

use App\Models\Services\Logs\ServiceSoldDeviceLog;
use App\Models\Services\ServiceSoldDeviceModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ServiceSoldDeviceObserver extends Conversion
{
    /**
     * Handle the ServiceSoldDeviceModel "created" event.
     */
    public function created(ServiceSoldDeviceModel $serviceSoldDeviceModel): void
    {
        try {
            DB::transaction(function () use ($serviceSoldDeviceModel) {
                ServiceSoldDeviceLog::query()
                    ->create([
                        'service_sold_device_id' => $serviceSoldDeviceModel->id,
                        'service_id' => $serviceSoldDeviceModel->service_id,
                        'equipment_id' => $serviceSoldDeviceModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'create',
                        'before' => null,
                        'after' => $this->convert($serviceSoldDeviceModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceSoldDeviceModel "updated" event.
     */
    public function updated(ServiceSoldDeviceModel $serviceSoldDeviceModel): void
    {
        try {
            DB::transaction(function () use ($serviceSoldDeviceModel) {
                ServiceSoldDeviceLog::query()
                    ->create([
                        'service_sold_device_id' => $serviceSoldDeviceModel->id,
                        'service_id' => $serviceSoldDeviceModel->service_id,
                        'equipment_id' => $serviceSoldDeviceModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'update',
                        'before' => $this->convert($serviceSoldDeviceModel->getOriginal()),
                        'after' => $this->convert($serviceSoldDeviceModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceSoldDeviceModel "deleted" event.
     */
    public function deleted(ServiceSoldDeviceModel $serviceSoldDeviceModel): void
    {
        try {
            DB::transaction(function () use ($serviceSoldDeviceModel) {
                ServiceSoldDeviceLog::query()
                    ->create([
                        'service_sold_device_id' => $serviceSoldDeviceModel->id,
                        'service_id' => $serviceSoldDeviceModel->service_id,
                        'equipment_id' => $serviceSoldDeviceModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'delete',
                        'before' => $this->convert($serviceSoldDeviceModel->getOriginal()),
                        'after' => null,
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceSoldDeviceModel "restored" event.
     */
    public function restored(ServiceSoldDeviceModel $serviceSoldDeviceModel): void
    {
        //
    }

    /**
     * Handle the ServiceSoldDeviceModel "force deleted" event.
     */
    public function forceDeleted(ServiceSoldDeviceModel $serviceSoldDeviceModel): void
    {
        //
    }
}
