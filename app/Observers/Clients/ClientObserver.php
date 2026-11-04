<?php

namespace App\Observers\Clients;

use App\Models\Clients\ClientModel;
use App\Models\Clients\Logs\ClientLogModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class ClientObserver extends Conversion
{
    /**
     * Handle the ClientModel "created" event.
     */
    public function created(ClientModel $clientModel): void
    {
        ClientLogModel::query()->create([
            'client_id' => $clientModel->id,
            'user_id' => Auth::user()->id ?? 6,
            'action' => 'create',
            'before' => null,
            'after' => $this->convert($clientModel->getAttributes()),
        ]);
    }

    /**
     * Handle the ClientModel "updated" event.
     */
    public function updated(ClientModel $clientModel): void
    {
        ClientLogModel::query()
            ->create([
                'client_id' => $clientModel->id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($clientModel->getOriginal()),
                'after' => $this->convert($clientModel->getAttributes()),
            ]);
    }

    /**
     * Handle the ClientModel "deleted" event.
     */
    public function deleted(ClientModel $clientModel): void
    {
        ClientLogModel::query()
            ->create([
                'client_id' => $clientModel->id,
                'user_id' => Auth::user()->id,
                'action' => 'delete',
                'before' => $this->convert($clientModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the ClientModel "restored" event.
     */
    public function restored(ClientModel $clientModel): void
    {
        //
    }

    /**
     * Handle the ClientModel "force deleted" event.
     */
    public function forceDeleted(ClientModel $clientModel): void
    {
        //
    }
}
