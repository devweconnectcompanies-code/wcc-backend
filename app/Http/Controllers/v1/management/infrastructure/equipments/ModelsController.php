<?php

namespace App\Http\Controllers\v1\management\infrastructure\equipments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Infrastructure\Equipments\ModelRequest;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\infrastructure\equipments\ModelService;
use App\Http\Resources\v1\management\infrastructure\equipments\ModelResource;
use App\Models\Infrastructure\Equipment\ModelModel;


class ModelsController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $query = ModelModel::query()
            ->with(['type:id,name', 'brand:id,name']);

        return $dataViewer->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'type' => fn($q, $data) => $q->whereIn('equipment_type_id', $data),
            'brand' => fn($q, $data) => $q->whereIn('brand_id', $data),
        ]);
    }

    public function store(ModelRequest $request, ModelService $service): JsonResponse
    {
        $model = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$model,
            'model' => new ModelResource($model),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'model' => ModelModel::query()->find($request->input('id'))
        ]);
    }

    public function update(ModelRequest $request, ModelModel $id, ModelService $service): JsonResponse
    {
        $model = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$model,
            'model' => new ModelResource($model),
        ]);
    }
}
