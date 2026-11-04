<?php

namespace App\Services\v1\management\billing\background;

use App\Enums\v1\Billing\InvoiceType;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;
use App\Services\v1\management\billing\invoices\ClientSelector;
use App\Services\v1\management\billing\invoices\InvoiceCreator;
use App\Services\v1\management\billing\invoices\InvoiceDataCalculator;
use App\Services\v1\management\billing\invoices\InvoiceValidator;
use App\Services\v1\management\billing\invoices\ItemCalculator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService
{
    private const CHUNK_SIZE = 50;

    public function __construct(
        private ClientSelector        $clientSelector,
        private InvoiceValidator      $invoiceValidator,
        private InvoiceDataCalculator $dataCalculator,
        private InvoiceCreator        $invoiceCreator,
        private ItemCalculator        $itemCalculator,
    )
    {

    }

    /***
     *  Genera las facturas de un período
     * @param PeriodModel $period
     * @param bool $allClients
     * @return array
     * @throws \Throwable
     */
    public function generateInvoicesForPeriod(PeriodModel $period, bool $allClients = false): array
    {
        $query = $this->clientSelector->getBillableClientsQuery($period, $allClients);

        $results = [
            'generated' => 0,
            'errors' => [],
            'total_clients' => $query->count(),
            'processed_chunks' => 0,
        ];

//        $results['total_clients'] = $query->count();

        Log::channel('invoices')
            ->info("Iniciando generación de facturas para el periodo {$period->code}", [
                'total_clients' => $results['total_clients'],
                'chunk_size' => self::CHUNK_SIZE,
            ]);

        $query->chunkById(self::CHUNK_SIZE, function ($clients) use ($period, &$results) {
            $results['processed_chunks']++;

            Log::channel('invoices')->info("Procesando chunk #{$results['processed_chunks']}", [
                'clients_in_chunk' => $clients->count(),
            ]);

            foreach ($clients as $client) {
                $client->load([
                    'services' => fn($q) => $q->activeOrUninstalledInPeriod($period)->with('internet.profile'),
                    'client_type',
                    'corporate_info'
                ]);

                try {
                    DB::transaction(function () use ($client, $period, &$results) {
                        $this->processClientInvoices($client, $period, $results);
                    });

                } catch (\Exception $e) {
                    $results['errors'][] = "Cliente {$client->id}: {$e->getMessage()}";
                    Log::channel('invoices')->error("Error procesando cliente {$client->id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            Log::channel('invoices')->info("Chunk #{$results['processed_chunks']} completado.", [
                'facturas_generadas_hasta_ahora' => $results['generated'],
            ]);
        }, 'id');

        Log::channel('invoices')->info("Generación de facturas completada.", $results);

        return $results;

    }

    /***
     *  Procesa las facturas de un cliente
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param array $results
     * @return void
     * @throws \Throwable
     */
    private function processClientInvoices(ClientModel $client, PeriodModel $period, array &$results): void
    {
        $separateServices = $client->services->filter(function ($service) {
            return $service->separate_billing === true;
        });
        $consolidatedServices = $client->services->filter(function ($service) {
            return $service->separate_billing === false;
        });

        foreach ($separateServices as $service) {
            $this->processIndividualInvoice($client, $period, $service, $results);
        }

        if ($consolidatedServices->isNotEmpty()) {
            $this->processConsolidatedInvoice($client, $period, $consolidatedServices, $results);
        }

    }

    /***
     *  Procesa factura individual para un servicio
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param ServiceModel $service
     * @param array $results
     * @return void
     * @throws \Throwable
     */
    private function processIndividualInvoice(
        ClientModel  $client,
        PeriodModel  $period,
        ServiceModel $service,
        array        &$results
    ): void
    {
        $uninstallationDate = $this->itemCalculator->getUninstallationDate($service, $period);

        if ($service->status_id !== true && !$uninstallationDate) {
            return;
        }

        if ($this->invoiceValidator->invoiceExistsForService($client, $period, $service)) {
            return;
        }

        if ($this->invoiceValidator->isServiceInConsolidatedInvoice($client, $period, $service)) {
            return;
        }

        $invoiceData = $this->dataCalculator->calculateForService($client, $period, $service);

        if (count($invoiceData['items']) !== 1) {
            throw new \Exception(
                "Error: Factura individual del servicio {$service->id} tiene " .
                count($invoiceData['items']) . " items en lugar de 1"
            );
        }

        $item = $invoiceData['items'][0];
        if ($item['service']->id !== $service->id) {
            throw new \Exception(
                "Error: El item calculado no corresponde al servicio solicitado"
            );
        }

        if ($invoiceData['total_amount'] > 0) {
            $this->invoiceCreator->create($client, $period, $invoiceData, InvoiceType::INDIVIDUAL->value);
            $results['generated']++;
        }
    }

    /***
     *  Procesa facturas consolidadas
     * @param ClientModel $client
     * @param PeriodModel $period
     * @param $consolidatedServices
     * @param array $results
     * @return void
     * @throws \Throwable
     */
    private function processConsolidatedInvoice(
        ClientModel $client,
        PeriodModel $period,
                    $consolidatedServices,
        array       &$results
    ): void
    {
        if ($consolidatedServices->isEmpty()) {
            return;
        }

        $validServices = $consolidatedServices->filter(function ($service) use ($period) {
            $uninstallationDate = $this->itemCalculator->getUninstallationDate($service, $period);

            return $service->status_id == CommonStatus::ACTIVE->value || $uninstallationDate;
        });

        if ($validServices->isEmpty()) {
            return;
        }

        if ($this->invoiceValidator->invoiceExists($client, $period)) {
            return;
        }

        $hasIndividualInvoices = false;

        foreach ($validServices as $service) {
            if ($this->invoiceValidator->invoiceExistsForService($client, $period, $service)) {
                $hasIndividualInvoices = true;
                break;
            }
        }

        if ($hasIndividualInvoices) {
            return;
        }

        $invoiceData = $this->dataCalculator->calculateForClient($client, $period, $validServices);

        if ($invoiceData['total_amount'] > 0) {
            $this->invoiceCreator->create($client, $period, $invoiceData, InvoiceType::CONSOLIDATED->value);
            $results['generated']++;
        }
    }

    /***
     * Estadísticas de facturación
     * @param PeriodModel $period
     * @return array
     */
    public function getBillingStatistics(PeriodModel $period): array
    {
        $invoices = InvoiceModel::query()
            ->where('billing_period_id', $period->id)
            ->get();

        return [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('total_amount'),
            'pending_amount' => $invoices->sum('balance_due'),
            'paid_amount' => $invoices->sum('paid_amount'),
        ];
    }
}
