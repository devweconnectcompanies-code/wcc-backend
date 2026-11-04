<?php

namespace App\Services\v1\management\billing\invoices;

use App\Enums\v1\Clients\ClientTypes;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;
use Illuminate\Support\Collection;

class InvoiceDataCalculator
{
    /***
     * @param ItemCalculator $itemCalculator
     */
    public function __construct(private ItemCalculator $itemCalculator)
    {

    }

    /***
     * Calcula datos para facturas consolidadas
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param Collection|null $services
     * @return array
     */
    public function calculateForClient(
        ClientModel $client,
        PeriodModel $period,
        ?Collection $services = null,
    ): array
    {
        $services = $services ?? $client->services;
        $items = [];

        foreach ($services as $service) {
            $uninstallationDate = $this->itemCalculator->getUninstallationDate($service, $period);

            if ($service->status_id != CommonStatus::ACTIVE->value && !$uninstallationDate) continue;

            $serviceItems = $this->itemCalculator->calculateForService($service, $period);
            $items = array_merge($items, $serviceItems);
        }

        return $this->calculateTotals($client, $items);
    }

    /***
     * Calcula datos para facturas individuales
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param ServiceModel $service
     * @return array
     */
    public function calculateForService(
        ClientModel  $client,
        PeriodModel  $period,
        ServiceModel $service,
    ): array
    {
        $items = $this->itemCalculator->calculateForService($service, $period);
        return $this->calculateTotals($client, $items);
    }

    /***
     * Calcula totales de las facturas
     * @param ClientModel $client
     * @param array $items
     * @return array
     */
    private function calculateTotals(ClientModel $client, array $items): array
    {
        $subtotal = collect($items)->sum('amount');
        $totalIva = collect($items)->sum('iva');
        $ivaRetenido = $this->calculateIvaRetenido($client, $subtotal);

        return [
            'total_amount' => $subtotal,
            'total_iva' => $totalIva,
            'iva_retenido' => $ivaRetenido,
            'items' => $items,
        ];
    }

    /***
     * Calcula IVA retenido
     * @param ClientModel $client
     * @param float $subtotal
     * @return float
     */
    public function calculateIvaRetenido(ClientModel $client, float $subtotal): float
    {
        if ($client->client_type_id != ClientTypes::CORPORATE->value) return 0;
        $corporateInfo = $client->corporate_info;
        if (!$corporateInfo || !$corporateInfo->retained_iva) return 0;

        return round($subtotal * 0.01, 8);
    }
}
