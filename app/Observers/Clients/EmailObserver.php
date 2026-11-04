<?php

namespace App\Observers\Clients;

use App\Models\Clients\EmailModel;
use App\Models\Clients\Logs\EmailLogModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class EmailObserver extends Conversion
{
    /**
     * Handle the EmailModel "created" event.
     */
    public function created(EmailModel $emailModel): void
    {
        EmailLogModel::query()
            ->create([
                'email_id' => $emailModel->id,
                'client_id' => $emailModel->client_id,
                'user_id' => Auth::user()->id ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($emailModel->getAttributes()),
            ]);
    }

    /**
     * Handle the EmailModel "updated" event.
     */
    public function updated(EmailModel $emailModel): void
    {
        EmailLogModel::query()
            ->create([
                'email_id' => $emailModel->id,
                'client_id' => $emailModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($emailModel->getOriginal()),
                'after' => $this->convert($emailModel->getAttributes()),
            ]);
    }

    /**
     * Handle the EmailModel "deleted" event.
     */
    public function deleted(EmailModel $emailModel): void
    {
        EmailLogModel::query()
            ->create([
                'email_id' => $emailModel->id,
                'client_id' => $emailModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'delete',
                'before' => $this->convert($emailModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the EmailModel "restored" event.
     */
    public function restored(EmailModel $emailModel): void
    {
        //
    }

    /**
     * Handle the EmailModel "force deleted" event.
     */
    public function forceDeleted(EmailModel $emailModel): void
    {
        //
    }
}
