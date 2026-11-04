<?php

namespace App\Observers\Services;

use App\Models\Services\Logs\ServiceEquipmentLogModel;
use App\Models\Services\ServiceEquipmentModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ServiceEquipmentObserver extends Conversion
{
    /**
     * Handle the ServiceEquipmentModel "created" event.
     */
    public function created(ServiceEquipmentModel $serviceEquipmentModel): void
    {
        try {
            DB::transaction(function () use ($serviceEquipmentModel) {
                ServiceEquipmentLogModel::query()
                    ->create([
                        'service_equipment_id' => $serviceEquipmentModel->id,
                        'service_id' => $serviceEquipmentModel->service_id,
                        'equipment_id' => $serviceEquipmentModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'create',
                        'before' => null,
                        'after' => $this->convert($serviceEquipmentModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceEquipmentModel "updated" event.
     */
    public function updated(ServiceEquipmentModel $serviceEquipmentModel): void
    {
        try {
            DB::transaction(function () use ($serviceEquipmentModel) {
                ServiceEquipmentLogModel::query()
                    ->create([
                        'service_equipment_id' => $serviceEquipmentModel->id,
                        'service_id' => $serviceEquipmentModel->service_id,
                        'equipment_id' => $serviceEquipmentModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'update',
                        'before' => $this->convert($serviceEquipmentModel->getOriginal()),
                        'after' => $this->convert($serviceEquipmentModel->getAttributes()),
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceEquipmentModel "deleted" event.
     */
    public function deleted(ServiceEquipmentModel $serviceEquipmentModel): void
    {
        try {
            DB::transaction(function () use ($serviceEquipmentModel) {
                ServiceEquipmentLogModel::query()
                    ->create([
                        'service_equipment_id' => $serviceEquipmentModel->id,
                        'service_id' => $serviceEquipmentModel->service_id,
                        'equipment_id' => $serviceEquipmentModel->equipment_id,
                        'user_id' => Auth::user()->id,
                        'action' => 'delete',
                        'before' => $this->convert($serviceEquipmentModel->getOriginal()),
                        'after' => null,
                    ]);
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Handle the ServiceEquipmentModel "restored" event.
     */
    public function restored(ServiceEquipmentModel $serviceEquipmentModel): void
    {
        //
    }

    /**
     * Handle the ServiceEquipmentModel "force deleted" event.
     */
    public function forceDeleted(ServiceEquipmentModel $serviceEquipmentModel): void
    {
        //
    }
}
