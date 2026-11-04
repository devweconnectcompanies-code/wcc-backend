<?php

namespace App\Observers\Clients;

use App\Models\Clients\DocumentModel;
use App\Models\Clients\Logs\DocumentLogModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class DocumentObserver extends Conversion
{
    /**
     * Handle the DocumentLogModel "created" event.
     */
    public function created(DocumentModel $documentModel): void
    {
        DocumentLogModel::query()
            ->create([
                'document_id' => $documentModel->id,
                'client_id' => $documentModel->client_id,
                'user_id' => Auth::id() ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($documentModel->getAttributes()),
            ]);
    }

    /**
     * Handle the DocumentLogModel "updated" event.
     */
    public function updated(DocumentModel $documentModel): void
    {
        DocumentLogModel::query()
            ->create([
                'document_id' => $documentModel->id,
                'client_id' => $documentModel->client_id,
                'user_id' => Auth::id(),
                'action' => 'update',
                'before' => $this->convert($documentModel->getOriginal()),
                'after' => $this->convert($documentModel->getAttributes()),
            ]);
    }

    /**
     * Handle the DocumentLogModel "deleted" event.
     */
    public function deleted(DocumentModel $documentModel): void
    {
        DocumentLogModel::query()
            ->create([
                'document_id' => $documentModel->id,
                'client_id' => $documentModel->client_id,
                'user_id' => Auth::id(),
                'action' => 'delete',
                'before' => $this->convert($documentModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the DocumentLogModel "restored" event.
     */
    public function restored(DocumentLogModel $documentLogModel): void
    {
        //
    }

    /**
     * Handle the DocumentLogModel "force deleted" event.
     */
    public function forceDeleted(DocumentLogModel $documentLogModel): void
    {
        //
    }
}
