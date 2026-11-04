<?php

namespace App\Http\Controllers\v1\management\billing\options;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\Options\StatusRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Billing\Options\StatusModel;
use App\Services\v1\management\billing\options\StatusService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $query = StatusModel::query();

        return $dataViewer->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(StatusRequest $request, StatusService $service): JsonResponse
    {
        $status = $service->createStatus($request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new GeneralResource($status),
        ]);
    }

    public function edit(Request $request, StatusService $service): JsonResponse
    {
        $status = $service->editStatus($request->input('id'));

        return response()->json([
            'status' => new GeneralResource($status),
        ]);
    }

    public function update(StatusRequest $request, StatusModel $id, StatusService $service): JsonResponse
    {
        $status = $service->updateStatus($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new GeneralResource($status),
        ]);
    }
}
