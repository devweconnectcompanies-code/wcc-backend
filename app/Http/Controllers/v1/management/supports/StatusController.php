<?php

namespace App\Http\Controllers\v1\management\supports;

use App\Http\Controllers\Controller;
use App\Models\Supports\StatusModel;
use App\Services\v1\management\supports\StatusService;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Supports\StatusRequest;
use Illuminate\Http\JsonResponse;
use App\Services\v1\management\DataViewerService;
use App\Http\Resources\v1\management\supports\StatusResource;

class StatusController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $query = StatusModel::query();

        return $dataViewer->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
        ]);
    }

    public function store(StatusRequest $request, StatusService $service): JsonResponse
    {
        $status = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new StatusResource($status),
        ]);
    }

    public function read(Request $request, StatusService $service): JsonResponse
    {
        $status = $service->read($request->input('id'));

        return response()->json([
            'status' => new StatusResource($status),
        ]);
    }

    public function update(StatusRequest $request, StatusModel $id, StatusService $service): JsonResponse
    {
        $status = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new StatusResource($status),
        ]);
    }
}
