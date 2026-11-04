<?php

namespace App\Observers\Clients;

use App\Models\Clients\FinancialInformationModel;
use App\Models\Clients\Logs\FinancialInformationLogModel;
use App\Observers\Base\Conversion;
use Illuminate\Support\Facades\Auth;

class FinancialInformationObserver extends Conversion
{
    /**
     * Handle the FinancialInformationModel "created" event.
     */
    public function created(FinancialInformationModel $financialInformationModel): void
    {
        FinancialInformationLogModel::query()
            ->create([
                'finance_information_id' => $financialInformationModel->id,
                'client_id' => $financialInformationModel->client_id,
                'user_id' => Auth::user()->id ?? 6,
                'action' => 'create',
                'before' => null,
                'after' => $this->convert($financialInformationModel->getAttributes()),
            ]);
    }

    /**
     * Handle the FinancialInformationModel "updated" event.
     */
    public function updated(FinancialInformationModel $financialInformationModel): void
    {
        FinancialInformationLogModel::query()
            ->create([
                'finance_information_id' => $financialInformationModel->id,
                'client_id' => $financialInformationModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'before' => $this->convert($financialInformationModel->getOriginal()),
                'after' => $this->convert($financialInformationModel->getAttributes()),
            ]);
    }

    /**
     * Handle the FinancialInformationModel "deleted" event.
     */
    public function deleted(FinancialInformationModel $financialInformationModel): void
    {
        FinancialInformationLogModel::query()
            ->create([
                'finance_information_id' => $financialInformationModel->id,
                'client_id' => $financialInformationModel->client_id,
                'user_id' => Auth::user()->id,
                'action' => 'delete',
                'before' => $this->convert($financialInformationModel->getOriginal()),
                'after' => null,
            ]);
    }

    /**
     * Handle the FinancialInformationModel "restored" event.
     */
    public function restored(FinancialInformationModel $financialInformationModel): void
    {
        //
    }

    /**
     * Handle the FinancialInformationModel "force deleted" event.
     */
    public function forceDeleted(FinancialInformationModel $financialInformationModel): void
    {
        //
    }
}
