<?php

namespace App\Jobs\billing;

use App\Services\v1\management\billing\invoices\InvoiceUpdater;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateInvoiceById implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $invoice_id,
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param InvoiceUpdater $updater
     * @return void
     * @throws \Throwable
     */
    public function handle(InvoiceUpdater $updater): void
    {
        $updater->recalculateInvoiceById(invoiceId: $this->invoice_id);
    }
}
