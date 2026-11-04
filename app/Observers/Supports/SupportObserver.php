<?php

namespace App\Observers\Supports;

use App\Models\Supports\LogModel;
use App\Models\Supports\SupportModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class SupportObserver extends Conversion
{
    /**
     * Handle the SupportModel "created" event.
     */
    public function created(SupportModel $support): void
    {
        LogModel::query()->create([
            'support_id' => $support->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'before' => null,
            'after' => $this->convert($support->getAttributes()),
        ]);
    }

    /**
     * Handle the SupportModel "updated" event.
     */
    public function updated(SupportModel $support): void
    {
        LogModel::query()->create([
            'support_id' => $support->id,
            'user_id' => Auth::id(),
            'action' => 'update',
            'before' => $this->convert($support->getOriginal()),
            'after' => $this->convert($support->getAttributes()),
        ]);
    }

    /**
     * Handle the SupportModel "deleted" event.
     */
    public function deleted(SupportModel $support): void
    {
        LogModel::query()->create([
            'support_id' => $support->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'before' => $this->convert($support->getOriginal()),
            'after' => null,
        ]);
    }

    /**
     * Handle the SupportModel "restored" event.
     */
    public function restored(SupportModel $supportModel): void
    {
        //
    }

    /**
     * Handle the SupportModel "force deleted" event.
     */
    public function forceDeleted(SupportModel $supportModel): void
    {
        //
    }
}
