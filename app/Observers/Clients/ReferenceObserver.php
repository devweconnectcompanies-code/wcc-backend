<?php

namespace App\Observers\Clients;

use App\Models\Clients\Logs\ReferenceLogModel;
use App\Models\Clients\ReferenceModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class ReferenceObserver extends Conversion
{
    /**
     * Handle the ReferenceModel "created" event.
     */
    public function created(ReferenceModel $referenceModel): void
    {
        ReferenceLogModel::query()
            ->create([
                'reference_id' => $referenceModel->id,
                'client_id' => $referenceModel->client_id,
                'user_id' => Auth::user()->id ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($referenceModel->getAttributes()),
            ]);
    }

    /**
     * Handle the ReferenceModel "updated" event.
     */
    public function updated(ReferenceModel $referenceModel): void
    {
        ReferenceLogModel::query()
            ->create([
                'reference_id' => $referenceModel->id,
                'client_id' => $referenceModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($referenceModel->getOriginal()),
                'after' => $this->convert($referenceModel->getAttributes()),
            ]);
    }

    /**
     * Handle the ReferenceModel "deleted" event.
     */
    public function deleted(ReferenceModel $referenceModel): void
    {
        ReferenceLogModel::query()
            ->create([
                'reference_id' => $referenceModel->id,
                'client_id' => $referenceModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'delete',
                'before' => $this->convert($referenceModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the ReferenceModel "restored" event.
     */
    public function restored(ReferenceModel $referenceModel): void
    {
        //
    }

    /**
     * Handle the ReferenceModel "force deleted" event.
     */
    public function forceDeleted(ReferenceModel $referenceModel): void
    {
        //
    }
}
