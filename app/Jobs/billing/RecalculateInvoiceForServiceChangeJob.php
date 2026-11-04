<?php

namespace App\Jobs\billing;

use App\Enums\v1\Billing\ServiceChangeEventTypesEnum;
use App\Models\Services\ServiceModel;
use App\Services\v1\management\billing\invoices\InvoiceUpdater;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateInvoiceForServiceChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int                         $service_id,
        public ServiceChangeEventTypesEnum $changeType,
        public array                       $changeData = [],
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(InvoiceUpdater $invoiceUpdater): void
    {
        $service = ServiceModel::query()->findOrFail($this->service_id);
        if (!$service) {
            return;
        }

        $invoiceUpdater->updateInvoicesForServiceChange(
            $service,
            $this->changeType,
            $this->changeData
        );
    }
}
