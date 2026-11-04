<?php

namespace App\Http\Controllers\v1\management\configuration\infrastructure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Configuration\Infrastructure\EquipmetStatusRequest;
use App\Http\Resources\v1\management\configuration\infrastructure\EquipmentStatusResource;
use Illuminate\Http\JsonResponse;
use App\Models\Configuration\Infrastructure\EquipmentStatusModel;
use App\Services\v1\management\configuration\infrastructure\equipment\EquipmentStatusService;
use App\Services\v1\management\DataViewerService;


class EquipmentStatusController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = EquipmentStatusModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data)
        ]);
    }

    public function store(EquipmetStatusRequest $request, EquipmentStatusService $service): JsonResponse
    {
        $status = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new equipmentStatusResource($status),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'status' => EquipmentStatusModel::query()->find($request->input('id')),
        ]);
    }

    public function update(EquipmetStatusRequest $request, EquipmentStatusModel $id, EquipmentStatusService $service): JsonResponse
    {
        $status = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new equipmentStatusResource($status),
        ]);
    }
}
