<?php

namespace App\Http\Controllers\v1\management\infrastructure\network;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Infrastructure\Network\EquipmentRequest;
use App\Services\v1\management\infrastructure\network\EquipmentService;
use App\Services\v1\management\DataViewerService;
use App\Http\Resources\v1\management\infrastructure\network\EquipmentResource;
use App\Models\Infrastructure\Network\EquipmentModel;

class EquipmentController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = EquipmentModel::query()->with([
            'type:id,name',
            'brand:id,name',
            'model:id,name',
            'node:id,name',
            'status:id,name,badge_color AS color',
        ]);

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'type' => fn($q, $data) => $q->whereIn('type_id', $data),
            'brand' => fn($q, $data) => $q->whereIn('brand_id', $data),
            'model' => fn($q, $data) => $q->whereIn('model_id', $data),
            'node' => fn($q, $data) => $q->whereIn('node_id', $data),
        ]);
    }

    public function store(EquipmentRequest $request, EquipmentService $service): JsonResponse
    {
        $equipment = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$equipment,
            'equipment' => new EquipmentResource($equipment)
        ]);
    }

    public function edit(Request $request, EquipmentService $service): JsonResponse
    {
        $equipment = $service->find($request->input('id'));

        return response()->json([
            'equipment' => new EquipmentResource($equipment),
        ]);
    }

    public function update(EquipmentRequest $request, EquipmentModel $id, EquipmentService $service): JsonResponse
    {
        $equipment = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$equipment,
            'equipment' => new EquipmentResource($equipment)
        ]);
    }

}
