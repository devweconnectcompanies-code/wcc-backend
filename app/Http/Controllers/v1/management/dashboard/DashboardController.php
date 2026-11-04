<?php

namespace App\Http\Controllers\v1\management\dashboard;

use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PaymentInvoiceModel;
use App\Models\Billing\PaymentModel;
use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use App\Models\Configuration\Clients\ClientTypeModel;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Models\Management\Profiles\InternetModel;
use App\Models\Services\ServiceModel;
use App\Models\Supports\SupportModel;
use App\Services\v1\management\dashboard\DashboardMikrotikService;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Conteo de clientes según la categoría de este.
     *
     * @return JsonResponse
     */
    public function clientsByType(): JsonResponse
    {
        $types = ClientTypeModel::query()
            ->withCount([
                'clients as total_clients' => function ($query) {
                    $query->where('status_id', true);
                }
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'labels' => $types->pluck('name'),
            'data' => $types->pluck('total_clients'),
        ]);
    }

    /**
     * Información del hardware del servidor de autenticación.
     *
     * @param DashboardMikrotikService $mikrotikService
     * @return JsonResponse
     */
    public function systemResources(DashboardMikrotikService $mikrotikService): JsonResponse
    {
//        $server = $this->authServer();
//
//        $data = $mikrotikService->getSystemResources(
//            host: $server->ip,
//            user: $server->user,
//            pass: $server->secret,
//            port: $server->port,
//        );
//
//        return response()->json([
//            'data' => new GeneralResource($data),
//        ]);

        $serverList = AuthServerModel::query()
            ->where('status_id', CommonStatus::ACTIVE)
            ->get();
        $data = [];
        foreach ($serverList as $server) {
            $serverData = $mikrotikService->getSystemResources(
                host: $server->ip,
                user: $server->user,
                pass: $server->secret,
                port: $server->port,
            );

            $data[] = $serverData;
        }

        return response()->json(['data' => new GeneralResource($data)]);
    }


    /**
     * Obtiene los 5 perfiles con más usuarios.
     *
     * @return JsonResponse
     */
    public function topInternetProfiles(): JsonResponse
    {
        $profiles = InternetModel::query()
            ->withCount([
                'service_internet as total_services' => function ($query) {
                    $query->where('status_id', true);
                }
            ])
            ->orderByDesc('total_services')
            ->limit(10)
            ->get(['id', 'name', 'price']);

        return response()->json([
            'labels' => $profiles->map(fn($profile) => sprintf(
                '%s ($%.2f)',
                $profile->name,
                $profile->price
            )),
            'data' => $profiles->pluck('total_services'),
        ]);
    }

    /**
     * Obtiene los soportes solucionados desde el inicio del mes en curso
     * hasta el día de ahora y los agrupa por tipo de soporte.
     *
     * @return JsonResponse
     */
    public function supportsByDay(): JsonResponse
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        $rows = SupportModel::query()
            ->where('status_id', 3)
            ->whereBetween('closed_at', [$startDate, $endDate])
            ->whereIn('type_id', [1, 2, 3, 4, 6, 7, 8])
            ->selectRaw("
                DATE(closed_at) as day,
                CASE
                    WHEN type_id IN (1,2) THEN 'Instalaciones'
                    WHEN type_id IN (3,4) THEN 'Soportes'
                    WHEN type_id IN (6,7) THEN 'Renovaciones'
                    WHEN type_id = 8 THEN 'Desinstalaciones'
                END as category,
                COUNT(*) AS total
            ")
            ->groupBy(
                DB::raw('DATE(closed_at)'),
                DB::raw("
                    CASE
                        WHEN type_id IN (1,2) THEN 'Instalaciones'
                        WHEN type_id IN (3,4) THEN 'Soportes'
                        WHEN type_id IN (6,7) THEN 'Renovaciones'
                        WHEN type_id = 8 THEN 'Desinstalaciones'
                    END
                ")
            )
            ->orderBy('day')
            ->get();

        //  Fechas para el eje X
        $period = CarbonPeriod::create(now()->startOfMonth(), now()->today());
        $categories = collect($period)->map(fn($date) => $date->day)->values();

        $period = CarbonPeriod::create($startDate, $endDate);

        //  Categorías que aparecerán como series
        $seriesName = [
            'Instalaciones',
            'Soportes',
            'Renovaciones',
            'Desinstalaciones',
        ];

        $series = collect($seriesName)->map(function ($category) use ($rows, $period) {
            return [
                'name' => $category,
                'data' => collect($period)
                    ->map(function ($date) use ($rows, $category) {
                        $record = $rows->first(function ($item) use ($date, $category) {
                            return $item->day === $date->format('Y-m-d') && $item->category === $category;
                        });

                        return (int)$record?->total;
//                        return (int)random_int(0, 50);
                    })->values()->all(),
            ];
        })->values();

        return response()->json([
            'categories' => $categories,
            'series' => $series,
        ]);
    }

    /**
     * Obtiene la cantidad de facturas emitidas, pendientes, pagadas, vencidas, parcialmente pagadas.
     *
     * @return JsonResponse
     */
    public function invoiceStatusChart(): JsonResponse
    {
        $period = $this->getPeriod();

        $labels = [];
        $series = [];

        $totals = collect();

        if ($period) {
            $totals = InvoiceModel::query()
                ->where([
                    ['status_id', true],
                    ['billing_period_id', $period->id],
                ])
                ->selectRaw('billing_status_id, COUNT(*) as total')
                ->groupBy('billing_status_id')
                ->pluck('total', 'billing_status_id');
        }

        foreach (BillingStatus::cases() as $status) {
            $labels[] = $status->label();
            $series[] = (int)($totals[$status->value] ?? 0);
        }

        return response()->json([
            'labels' => $labels,
            'series' => $series,
            'period' => $period?->name,
        ]);
    }

    /**
     * Datos de tráfico de las interfaces.
     *
     * @param DashboardMikrotikService $mikrotikService
     * @return JsonResponse
     */
    public function interfaceTraffic(DashboardMikrotikService $mikrotikService): JsonResponse
    {
        $server = $this->authServer();
        $data = $mikrotikService->getMultipleInterfacesTraffic(
            host: $server->ip,
            user: $server->user,
            pass: $server->secret,
            port: $server->port,
        );

        return response()->json([
            'data' => new GeneralResource($data),
        ]);
    }

    /**
     * Obtiene las interfaces activas junto con el acumulado de ellas.
     *
     * @param DashboardMikrotikService $mikrotikService
     * @return JsonResponse
     */
    public function interfacesList(DashboardMikrotikService $mikrotikService): JsonResponse
    {
        $server = $this->authServer();
        $data = $mikrotikService->getInterfaceList(
            host: $server->ip,
            user: $server->user,
            pass: $server->secret,
            port: $server->port,
        );
        return response()->json([
            'data' => new GeneralResource($data),
        ]);
    }

    /**
     * Listado de sesiones activas en el dispositivo.
     *
     * @param DashboardMikrotikService $mikrotikService
     * @return JsonResponse
     */
    public function activeSessions(DashboardMikrotikService $mikrotikService): JsonResponse
    {
        $server = $this->authServer();
        $data = $mikrotikService->getActiveSessions(
            host: $server->ip,
            user: $server->user,
            pass: $server->secret,
            port: $server->port,
        );
        return response()->json([
            'data' => new GeneralResource($data),
        ]);
    }

    /**
     * Cantidad de clientes activos.
     *
     * @return JsonResponse
     */
    public function statsActiveClients(): JsonResponse
    {
        return response()->json([
            'response' => ClientModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->count()
        ]);
    }

    /**
     * Cantidad de servicios activos.
     *
     * @return JsonResponse
     */
    public function statsActiveServices(): JsonResponse
    {
        return response()->json([
            'response' => ServiceModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->count()
        ]);
    }

    /**
     * Retorna los ingresos de facturas pagadas durante el mes en curso.
     *
     * @return JsonResponse
     */
    public function statsMonthlyIncomes(): JsonResponse
    {
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();
        $total = PaymentModel::query()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json([
            'response' => (float)$total,
        ]);
    }

    /**
     * Obtiene el monto de las facturas, emitidas, pendientes y vencidas.
     *
     * @return JsonResponse
     */
    public function statsMonthlyPendingIncomes(): JsonResponse
    {
        $period = $this->getPeriod();

        if (!$period) {
            return response()->json([
                'data' => '0.00',
                'period' => 'Periodo indefinido',
            ]);
        }
        $total = InvoiceModel::query()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->where('billing_period_id', $period->id)
            ->whereIn('billing_status_id', [
                BillingStatus::ISSUED->value,
                BillingStatus::PENDING->value,
                BillingStatus::OVERDUE->value,
                BillingStatus::PARTIALLY_PAID->value,
            ])
            ->sum('balance_due');

        return response()->json([
            'response' => (float)$total,
        ]);
    }

    /**
     * Datos del servidor de autenticación.
     *
     * @return AuthServerModel
     */
    private function authServer(): AuthServerModel
    {
        return AuthServerModel::query()->findOrFail(config('mikrotik.main_server'));
    }

    /**
     * Obtiene el último periodo con facturas generadas.
     *
     * @return PeriodModel
     */
    private function getPeriod(): PeriodModel
    {
        return PeriodModel::query()
            ->where('status_id', true)
            ->whereHas('invoices', function ($query) {
                $query->where('status_id', true);
            })
            ->orderByDesc('period_start')
            ->first();
    }

    /**
     * Suma de los pagos realizados por día, desde el inicio del mes en curso hasta el día de hoy.
     *
     * @return JsonResponse
     */
    public function dailyPayments(): JsonResponse
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->startOfDay();

        $totals = PaymentModel::query()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('DATE(payment_date) as day, SUM(amount) as total')
            ->groupBy(DB::raw('DATE(payment_date)'))
            ->pluck('total', 'day');

        $categories = [];
        $data = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $categories[] = $date->day;
            $data[] = round((float)($totals[$date->toDateString()] ?? 0), 2);
        }

        return response()->json([
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Pagos recibidos',
                    'data' => $data,
                ],
            ],
            'total' => round(array_sum($data), 2),
        ]);
    }
}
