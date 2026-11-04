<?php

namespace App\Http\Controllers\v1\management\infrastructure\equipments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Infrastructure\Equipments\TypesRequest;
use App\Http\Resources\v1\management\infrastructure\equipments\TypesResource;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\infrastructure\equipments\TypesService;
use App\Models\Infrastructure\Equipment\TypeModel;

class TypesController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = TypeModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
        ]);
    }

    public function store(TypesRequest $request, TypesService $service): JsonResponse
    {
        $type = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$type,
            'type' => new TypesResource($type)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'type' => TypeModel::query()->find($request->input('id')),
        ]);
    }

    public function update(TypesRequest $request, TypeModel $id, TypesService $service): JsonResponse
    {
        $type = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$type,
            'type' => new TypesResource($type)
        ]);
    }
}
