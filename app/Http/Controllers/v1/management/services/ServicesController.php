<?php

namespace App\Http\Controllers\v1\management\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Services\ServiceRequest;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\services\ServService;
use App\Http\Resources\v1\management\services\ServiceResource;
use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;

class ServicesController extends Controller
{
    public function data(Request $request, DataViewerService $viewerService): JsonResponse
    {
        $clients = ClientModel::query()
            ->with([
                'branch',
                'client_type',
                'dui',
                'mobile',
                'address.state',
                'address.district',
            ])
            ->withCount('services');
//            ->where('status_id', 1);

        return $viewerService->handle($request, $clients, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'branch' => fn($q, $data) => $q->whereIn('branch_id', $data),
            'type' => fn($q, $data) => $q->whereIn('client_type_id', $data),
            'state' => fn($q, $data) => $q->whereHas('address', function ($q) use ($data) {
                return $q->where('state_id', $data);
            }),
            'district' => fn($q, $data) => $q->whereHas('address', function ($q) use ($data) {
                return $q->where('district_id', $data);
            })
        ]);
    }

    public function store(ServiceRequest $request, ServService $servService): JsonResponse
    {
        $service = $servService->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$service,
            'service' => new ServiceResource($service),
        ]);
    }

    public function read(Request $request, ServService $servService): JsonResponse
    {
        $service = $servService->read($request->input('id'));

        return response()->json([
            'service' => new ServiceResource($service),
        ]);
    }

    public function update(ServiceRequest $request, ServiceModel $id, ServService $servService): JsonResponse
    {
        $service = $servService->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$service,
            'service' => new ServiceResource($service),
        ]);
    }

    public function clientServices(Request $request, ServService $servService): JsonResponse
    {
        return response()->json([
            'client' => new ServiceResource($servService->clientServices($request->input('client'))),
        ]);
    }

    public function servicesList(int $clientID, ServService $service): JsonResponse
    {
        return response()->json([
            'response' => new ServiceResource($service->supportList($clientID))
        ]);
    }

    public function serviceAddress(int $serviceID, ServService $service): JsonResponse
    {
        return response()->json([
            'address' => new ServiceResource($service->getAddress($serviceID))
        ]);
    }
}
