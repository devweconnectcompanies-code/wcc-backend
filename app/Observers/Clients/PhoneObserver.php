<?php

namespace App\Observers\Clients;

use App\Models\Clients\Logs\PhoneLogModel;
use App\Models\Clients\PhoneModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class PhoneObserver extends Conversion
{
    /**
     * Handle the PhoneModel "created" event.
     */
    public function created(PhoneModel $phoneModel): void
    {
        PhoneLogModel::query()
            ->create([
                'phone_id' => $phoneModel->id,
                'client_id' => $phoneModel->client_id,
                'user_id' => Auth::user()->id ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($phoneModel->getAttributes()),
            ]);
    }

    /**
     * Handle the PhoneModel "updated" event.
     */
    public function updated(PhoneModel $phoneModel): void
    {
        PhoneLogModel::query()
            ->create([
                'phone_id' => $phoneModel->id,
                'client_id' => $phoneModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($phoneModel->getOriginal()),
                'after' => $this->convert($phoneModel->getAttributes()),
            ]);
    }

    /**
     * Handle the PhoneModel "deleted" event.
     */
    public function deleted(PhoneModel $phoneModel): void
    {
        PhoneLogModel::query()
            ->create([
                'phone_id' => $phoneModel->id,
                'client_id' => $phoneModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'delete',
                'before' => $this->convert($phoneModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the PhoneModel "restored" event.
     */
    public function restored(PhoneModel $phoneModel): void
    {
        //
    }

    /**
     * Handle the PhoneModel "force deleted" event.
     */
    public function forceDeleted(PhoneModel $phoneModel): void
    {
        //
    }
}
