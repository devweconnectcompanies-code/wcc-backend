<?php

namespace App\Http\Controllers\v1\management\supports;

use App\Http\Controllers\Controller;
use App\Models\Supports\TypeModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\v1\management\DataViewerService;
use App\Http\Resources\v1\management\supports\TypesResource;
use App\Services\v1\management\supports\TypesService;
use App\Http\Requests\v1\Management\Supports\TypesRequest;

class TypesController extends Controller
{
    public function data(Request $request, DataViewerService $viewerService): JsonResponse
    {
        $query = TypeModel::query();

        return $viewerService->handle($request, $query, [
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

    public function read(Request $request, TypesService $service): JsonResponse
    {
        $type = $service->read($request->input('id'));

        return response()->json([
            'type' => new TypesResource($type)
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
