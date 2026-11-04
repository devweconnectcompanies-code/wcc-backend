<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\PhoneTypeRequest;
use App\Http\Resources\v1\management\configuration\clients\PhoneTypeResource;
use App\Services\v1\management\configuration\clients\PhoneTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Configuration\Clients\PhoneTypeModel;
use App\Services\v1\management\DataViewerService;

class PhoneTypeController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = PhoneTypeModel::query();
        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(PhoneTypeRequest $request, PhoneTypeService $phoneTypeService): JsonResponse
    {
        $type = $phoneTypeService->createType($request->toDTO());

        return response()->json([
            'saved' => (bool)$type,
            'type' => new PhoneTypeResource($type),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['type' => PhoneTypeModel::query()->findOrFail($request->id)]);
    }

    public function update(PhoneTypeRequest $request, PhoneTypeModel $id, PhoneTypeService $phoneTypeService): JsonResponse
    {
        $type = $phoneTypeService->updateType($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$type,
            'type' => new PhoneTypeResource($type),
        ]);
    }
}
