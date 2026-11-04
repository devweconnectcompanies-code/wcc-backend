<?php

namespace App\Http\Controllers\v1\management\Monitoring;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Models\Monitoring\ActiveConnectionModel;
use App\Models\Services\ServiceInternetModel;
use App\Models\Services\ServiceModel;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\supports\SupportService;
use App\Services\v1\monitoring\MikrotikConnectionSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternetController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = ActiveConnectionModel::query()
            ->with([
                'client.dui',
                'client.branch',
                'client.mobile',
                'client.financial_status.status',
            ]);

        return $service->handle($request, $query, [
            'financial_status' => fn($q, $data) => $q->whereHas('client.financial_status', function ($q) use ($data) {
                return $q->whereIn('status_id', $data);
            }),
        ]);
    }

    /**
     * Busca un usuario PPPoE en equipo Mikrotik, y obtiene perfil, datos de navegación y datos generales de conexión.
     *
     * @param Request $request
     * @param MikrotikConnectionSyncService $service
     * @return JsonResponse
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     */
    public function pppoeInfo(Request $request, MikrotikConnectionSyncService $service): JsonResponse
    {
        $pppoe = trim($request->pppoe_user);
//        $user = ServiceInternetModel::query()
//            ->with(['service.node.auth_server'])
//            ->where('user', $pppoe)
//            ->first();
//        $server = $user->service->node->auth_server;
        $server = AuthServerModel::query()->findOrFail(config('mikrotik.main_server'));
        $data = $service->findActiveConnectionByUser(
            host: $server->ip,
            user: $server->user,
            pass: $server->secret,
            pppoeUser: $pppoe,
            port: $server->port,
        );

        return response()->json([
            'response' => new GeneralResource($data),
        ]);
    }

    public function printLastSupport(int $service_id, SupportService $service): Response
    {
        $query = ServiceModel::query()
            ->with([
                'last_support',
                'last_support.branch.state:id,name',
                'last_support.branch.municipality:id,name',
                'last_support.branch.district:id,name',
                'last_support.client.dui',
                'last_support.client.nit',
                'last_support.client.passport',
                'last_support.client.residence',
                'last_support.client.mobile',
                'last_support.contract',
                'last_support.state',
                'last_support.municipality',
                'last_support.district',
                'last_support.details',
                'last_support.type',
            ])
            ->findOrFail($service_id);
        $binary = $service->printTicket($query->last_support);

        return response($binary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename=ticket.pdf');
    }
}
