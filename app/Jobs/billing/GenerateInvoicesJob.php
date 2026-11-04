<?php

namespace App\Jobs\billing;

use App\Models\Billing\PeriodModel;
use App\Services\v1\management\billing\background\BillingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int  $periodId,
        public bool $allClients = false,
        public bool $stats = false,
    )
    {
        //
    }

    /**
     * @param BillingService $billingService
     * @return void
     * @throws \Throwable
     */
    public function handle(BillingService $billingService): void
    {
        $period = PeriodModel::query()->findOrFail($this->periodId);
        $billingService->generateInvoicesForPeriod($period, $this->allClients);

        if ($this->stats) $billingService->getBillingStatistics($period);
    }
}
