<?php

namespace App\Services\v1\management\billing\background;

use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\InvoiceModel;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Services\v1\network\MikrotikInternetService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class OverdueServiceCutService
{
    /**
     * Procesa el corte de los servicios morosos.
     * Solo se cortan los servicios que aparecen en los ítems de las facturas vencidas (sin prórroga activa),
     * de modo que un cliente con varios servicios no pierde los que están solventes.
     * Agrupa los servicios a cortar por servidor de autenticación para abrir una única conexión Mikrotik por
     * servidor en lugar de por servicio.
     *
     * @param bool $dryRun
     * @return void
     */
    public function cutOverdueClients(bool $dryRun = false): void
    {
        Log::channel('cut-service')->info('Iniciando corte de servicios morosos', ['dryRun' => $dryRun]);

        $serviceByNode = [];

        InvoiceModel::query()
            ->with([
                'items' => fn($q) => $q
                    ->where('status_id', CommonStatus::ACTIVE->value)
                    ->whereNotNull('service_id'),
                'items.service' => fn($q) => $q->where('status_id', CommonStatus::ACTIVE->value),
                'items.service.internet.profile',
            ])
            ->where('billing_status_id', BillingStatus::OVERDUE->value)
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereDoesntHave('extensions', function ($q) {
                $q->where('status_id', CommonStatus::ACTIVE->value)
                    ->whereDate('extended_due_date', '>=', Carbon::now());
            })
            ->chunkById(100, function ($invoices) use (&$serviceByNode) {
                foreach ($invoices as $invoice) {
                    foreach ($invoice->items as $item) {
                        $service = $item->service;

                        //  Servicio inactivo/eliminado: no hay nada que cortar
                        if (!$service) continue;

                        $credentials = $service->internet;

                        if (!$credentials) {
                            Log::channel('cut-service')
                                ->error("Credenciales no encontradas para el servicio {$service->id}", [
                                    'client_id' => $invoice->client_id,
                                    'invoice_id' => $invoice->id,
                                ]);
                            continue;
                        }

                        /**
                         * Indexando por service_id: si el mismo servicio aparece en más de una factura
                         * vencida (o en varios ítems), no se duplica
                         */
                        $serviceByNode[$service->node_id][$service->id] = [
                            'client_id' => $invoice->client_id,
                            'user' => $credentials->user,
                            'debt_profile' => $credentials->profile?->debt_profile,
                        ];
                    }
                }
            });

        $totalServices = array_sum(array_map('count', $serviceByNode));

        if ($dryRun) {
            Log::channel('cut-service')
                ->info("Dry-run: se cortarían los servicios", [
                    'total_services' => $totalServices,
                    'total_nodos' => count($serviceByNode),
                    'servicio_por_nodo' => array_map('array_keys', $serviceByNode),
                ]);
            return;
        }

        foreach ($serviceByNode as $nodeId => $services) {
            $this->cutServicesOnNode(nodeId: (int)$nodeId, services: $services);
        }

        Log::channel('cut-service')->info("Finalizando corte de servicios morosos", [
            'total_services' => $totalServices,
            'total_nodos' => count($serviceByNode),
        ]);
    }

    /**
     * Corta todos los servicios de un mismo servidor Mikrotik en una sola conexión
     *
     * @param int $nodeId
     * @param array $services
     * @return void
     */
    private function cutServicesOnNode(int $nodeId, array $services): void
    {
        try {
            $server = AuthServerModel::query()->findOrFail($nodeId)->toArray();
        } catch (Throwable $e) {
            Log::channel('cut-service')
                ->error("No se pudo obtener el servidor Mikrotik para cortar servicios", [
                    'node_id' => $nodeId,
                    'servicios' => array_keys($services),
                    'error' => $e->getMessage(),
                ]);
            return;
        }

        $updates = [];

        foreach ($services as $serviceId => $data) {
            if (!$data['user']) {
                Log::channel('cut-service')
                    ->error("Usuario PPPoE vacío para servicio {$serviceId}, se omite", [
                        'client_id' => $data['client_id'],
                        'node_id' => $nodeId,
                    ]);
                continue;
            }

            if (!$data['debt_profile']) {
                Log::channel('cut-service')
                    ->error("El perfil no tiene debt_profile configurado para servicio {$serviceId}, se omite", [
                        'client_id' => $data['client_id'],
                        'node_id' => $nodeId,
                    ]);
                continue;
            }

            $updates[] = [
                'name' => $data['user'],
                'data' => ['profile' => $data['debt_profile']],
            ];
        }

        if (empty($updates)) {
            return;
        }

        try {
            $mikrotikInternetService = app(MikrotikInternetService::class);
            $results = $mikrotikInternetService->updateMultipleUsers(server: $server, updates: $updates);

            foreach ($results as $username => $result) {
                if (!($result['OK'] ?? false)) {
                    Log::channel('cut-service')
                        ->error("Error conrtando servicio por morosidad", [
                            'node_id' => $nodeId,
                            'pppoe_user' => $username,
                            'error' => $result['error'] ?? 'desconocido',
                        ]);
                }
            }
        } catch (Throwable $e) {
            Log::channel('cut-service')
                ->error("Error al conectar con el nodo Mikrotik para el corte masivo", [
                    'node_id' => $nodeId,
                    'servicios' => array_keys($services),
                    'error' => $e->getMessage(),
                ]);
        }
    }
}
