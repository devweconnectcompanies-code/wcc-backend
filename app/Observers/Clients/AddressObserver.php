<?php

namespace App\Observers\Clients;

use App\Models\Clients\AddressModel;
use App\Models\Clients\Logs\AddressLogModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class AddressObserver extends Conversion
{
    /**
     * Handle the AddressModel "created" event.
     */
    public function created(AddressModel $addressModel): void
    {
        AddressLogModel::query()
            ->create([
                'address_id' => $addressModel->id,
                'client_id' => $addressModel->client_id,
                'user_id' => Auth::user()->id ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($addressModel->getAttributes()),
            ]);
    }

    /**
     * Handle the AddressModel "updated" event.
     */
    public function updated(AddressModel $addressModel): void
    {
        AddressLogModel::query()
            ->create([
                'address_id' => $addressModel->id,
                'client_id' => $addressModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($addressModel->getOriginal()),
                'after' => $this->convert($addressModel->getAttributes()),
            ]);
    }

    /**
     * Handle the AddressModel "deleted" event.
     */
    public function deleted(AddressModel $addressModel): void
    {
        AddressLogModel::query()
            ->create([
                'address_id' => $addressModel->id,
                'client_id' => $addressModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'delete',
                'before' => $this->convert($addressModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the AddressModel "restored" event.
     */
    public function restored(AddressModel $addressModel): void
    {
        //
    }

    /**
     * Handle the AddressModel "force deleted" event.
     */
    public function forceDeleted(AddressModel $addressModel): void
    {
        //
    }
}
