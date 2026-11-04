<?php

namespace App\Services\v1\management\billing\invoices;

use App\Enums\v1\Billing\InvoiceType;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;

class InvoiceValidator
{
    /***
     * Verifica si existe una factura consolidada
     * @param ClientModel $client
     * @param PeriodModel $period
     * @return bool
     */
    public function invoiceExists(ClientModel $client, PeriodModel $period): bool
    {
        return InvoiceModel::query()
            ->where([
                ['client_id', $client->id],
                ['billing_period_id', $period->id],
                ['invoice_type', InvoiceType::CONSOLIDATED->value]
            ])
            ->exists();
    }

    /***
     * Verifica si existe factura individual para un servicio
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param ServiceModel $service
     * @return bool
     */
    public function invoiceExistsForService(ClientModel $client, PeriodModel $period, ServiceModel $service): bool
    {
        return InvoiceModel::query()
            ->where([
                ['client_id', $client->id],
                ['billing_period_id', $period->id],
                ['invoice_type', InvoiceType::INDIVIDUAL->value]
            ])
            ->whereHas('items', function ($q) use ($service) {
                $q->where('service_id', $service->id);
            })
            ->exists();
    }

    /***
     * Verifica si un servicio está incluído en una factura consolidada
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param ServiceModel $service
     * @return bool
     */
    public function isServiceInConsolidatedInvoice(
        ClientModel  $client,
        PeriodModel  $period,
        ServiceModel $service
    ): bool
    {
        return InvoiceModel::query()
            ->where([
                ['client_id', $client->id],
                ['billing_period_id', $period->id],
                ['invoice_type', InvoiceType::CONSOLIDATED->value]
            ])
            ->whereHas('items', function ($q) use ($service) {
                $q->where('service_id', $service->id);
            })
            ->exists();
    }
}
