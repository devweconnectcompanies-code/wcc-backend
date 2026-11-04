<?php

namespace App\Observers\Billing;

use App\Models\Billing\InvoiceModel;
use App\Services\v1\management\billing\background\ClientFinancialStatusService;

class InvoiceObserver
{
    public function __construct(
        private ClientFinancialStatusService $financialStatusService
    )
    {
    }

    /***
     * Cuando se cra la factura actualiza el estado financiero
     * @param InvoiceModel $invoice
     * @return void
     */
    public function created(InvoiceModel $invoice): void
    {
        $this->financialStatusService->updateClientFinancialStatus($invoice->client_id, updateInvoiceStatuses: true);
    }

    /**
     * Actualiza estado financiero cuando se actualiza una factura
     * @param InvoiceModel $invoice
     * @return void
     */
    public function updated(InvoiceModel $invoice): void
    {
        if ($invoice->wasChanged(['paid_amount', 'balance_due', 'billing_status_id'])) {
            $this->financialStatusService->updateClientFinancialStatus($invoice->client_id, updateInvoiceStatuses: true);
        }
    }

    /***
     * Actualiza estado financiero cuando se elimina una factura
     * @param InvoiceModel $invoice
     * @return void
     */
    public function deleted(InvoiceModel $invoice): void
    {
        $this->financialStatusService->updateClientFinancialStatus($invoice->client_id, updateInvoiceStatuses: true);
    }
}
