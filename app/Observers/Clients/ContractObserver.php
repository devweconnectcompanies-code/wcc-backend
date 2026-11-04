<?php

namespace App\Observers\Clients;

use App\Models\Clients\ContractModel;
use App\Models\Clients\Logs\ContractLogModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class ContractObserver extends Conversion
{
    /**
     * Handle the ContractModel "created" event.
     */
    public function created(ContractModel $contractModel): void
    {
        ContractLogModel::query()
            ->create([
                'contract_id' => $contractModel->id,
                'client_id' => $contractModel->client_id,
                'user_id' => Auth::user()->id ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($contractModel->getAttributes()),
            ]);
    }

    /**
     * Handle the ContractModel "updated" event.
     */
    public function updated(ContractModel $contractModel): void
    {
        ContractLogModel::query()
            ->create([
                'contract_id' => $contractModel->id,
                'client_id' => $contractModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($contractModel->getOriginal()),
                'after' => $this->convert($contractModel->getAttributes()),
            ]);
    }

    /**
     * Handle the ContractModel "deleted" event.
     */
    public function deleted(ContractModel $contractModel): void
    {
        ContractLogModel::query()
            ->create([
                'contract_id' => $contractModel->id,
                'client_id' => $contractModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($contractModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the ContractModel "restored" event.
     */
    public function restored(ContractModel $contractModel): void
    {
        //
    }

    /**
     * Handle the ContractModel "force deleted" event.
     */
    public function forceDeleted(ContractModel $contractModel): void
    {
        //
    }
}
