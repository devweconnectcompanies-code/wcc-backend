<?php

namespace App\Observers\Services;

use App\Models\Services\Logs\ServiceIPTVEquipmentLog;
use App\Models\Services\ServiceIptvEquipmentModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ServiceIPTVEquipmentObserver extends Conversion
{
    /**
     * Handle the ServiceIptvEquipmentModel "created" event.
     */
    public function created(ServiceIptvEquipmentModel $serviceIptvEquipmentModel): void
    {
        try {
            DB::transaction(function () use ($serviceIptvEquipmentModel) {
                ServiceIPTVEquipmentLog::query()
                    ->create([
                        'service_iptv_equipment_id' => $serviceIptvEquipmentModel->id,
                        'service_id' => $serviceIptvEquipmentModel->service_id,
                        'equipment_id' => $serviceIptvEquipmentModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'create',
                        'before' => null,
                        'after' => $this->convert($serviceIptvEquipmentModel->getAttributes())
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceIptvEquipmentModel "updated" event.
     */
    public function updated(ServiceIptvEquipmentModel $serviceIptvEquipmentModel): void
    {
        try {
            DB::transaction(function () use ($serviceIptvEquipmentModel) {
                ServiceIPTVEquipmentLog::query()
                    ->create([
                        'service_iptv_equipment_id' => $serviceIptvEquipmentModel->id,
                        'service_id' => $serviceIptvEquipmentModel->service_id,
                        'equipment_id' => $serviceIptvEquipmentModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'update',
                        'before' => $this->convert($serviceIptvEquipmentModel->getOriginal()),
                        'after' => $this->convert($serviceIptvEquipmentModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceIptvEquipmentModel "deleted" event.
     */
    public function deleted(ServiceIptvEquipmentModel $serviceIptvEquipmentModel): void
    {
        try {
            DB::transaction(function () use ($serviceIptvEquipmentModel) {
                ServiceIPTVEquipmentLog::query()
                    ->create([
                        'service_iptv_equipment_id' => $serviceIptvEquipmentModel->id,
                        'service_id' => $serviceIptvEquipmentModel->service_id,
                        'equipment_id' => $serviceIptvEquipmentModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'delete',
                        'before' => $this->convert($serviceIptvEquipmentModel->getOriginal()),
                        'after' => null,
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceIptvEquipmentModel "restored" event.
     */
    public function restored(ServiceIptvEquipmentModel $serviceIptvEquipmentModel): void
    {
        //
    }

    /**
     * Handle the ServiceIptvEquipmentModel "force deleted" event.
     */
    public function forceDeleted(ServiceIptvEquipmentModel $serviceIptvEquipmentModel): void
    {
        //
    }
}
