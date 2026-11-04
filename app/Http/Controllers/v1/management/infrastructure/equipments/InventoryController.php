<?php

namespace App\Http\Controllers\v1\management\infrastructure\equipments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Infrastructure\Equipments\InventoryRequest;
use Illuminate\Http\JsonResponse;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\infrastructure\equipments\InventoryService;
use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Http\Resources\v1\management\infrastructure\equipments\InventoryResource;

class InventoryController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $query = InventoryModel::query()
            ->with([
                'brand:id,name',
                'type:id,name',
                'model:id,name',
                'branch:id,name',
                'status:id,name,badge_color',
                'on_internet_service.service.client',
                'on_iptv_service.service.client',
                'on_sold_devices.service.client',
            ]);

        return $dataViewer->handle($request, $query, [
            'brand' => fn($q, $data) => $q->whereIn('brand_id', $data),
            'type' => fn($q, $data) => $q->whereIn('type_id', $data),
            'model' => fn($q, $data) => $q->whereIn('model_id', $data),
            'branch' => fn($q, $data) => $q->whereIn('branch_id', $data),
            'technician' => fn($q, $data) => $q->whereIn('technician_id', $data),
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'company' => fn($q, $data) => $q->whereIn('company_id', $data),
        ]);
    }

    public function store(InventoryRequest $request, InventoryService $service): JsonResponse
    {
        $validatedData = $request->validated();
        $result = $service->create($validatedData, $request->file('file'));

        if ($result['success']) {
            return response()->json([
                'saved' => true,
                'message' => 'Importación completa',
                'results' => $result['results'],
            ]);
        } else {
            return response()->json([
                'saved' => false,
                'message' => $result['error'],
            ], 422);
        }
    }

    public function read(Request $request, InventoryService $service): JsonResponse
    {
        return response()->json([
            'equipment' => new InventoryResource($service->read($request->input('id')))
        ]);
    }

    public function update(InventoryRequest $request, InventoryModel $id, InventoryService $service): JsonResponse
    {
        $equipment = $service->update($id, $request);

        return response()->json([
            'saved' => (bool)$equipment,
            'equipment' => new InventoryResource($equipment),
            'results' => [
                'errors' => []
            ],
        ]);
    }

    public function logs(Request $request, InventoryService $service): JsonResponse
    {
        $logs = $service->logs($request->input('id'));

        return response()->json([
            'equipment' => new InventoryResource($logs),
        ]);
    }

    public function internet_search(Request $request, InventoryService $service): JsonResponse
    {
        $mac = $service->internet_search($request->input('mac'));
        return response()->json([
            'equipment' => new InventoryResource($mac->makeHidden('company')),
        ]);
    }

    public function iptv_search(Request $request, InventoryService $service): JsonResponse
    {
        $mac = $service->tvBoxSearch($request->input('mac'));

        return response()->json([
            'equipment' => new InventoryResource($mac->makeHidden('company')),
        ]);
    }

    public function sales_search(Request $request, InventoryService $service): JsonResponse
    {
        $device = $service->salesSearch($request->input('mac'));
        return response()->json([
            'device' => new InventoryResource($device)
        ]);
    }

    public function sell_device(Request $request, InventoryService $service): JsonResponse
    {
        $device = $service->sell($request->input('device'), $request->input('service'), $request->input('client'));
        return response()->json([
            'saved' => (bool)$device,
            'device' => new InventoryResource($device)
        ]);
    }
}
