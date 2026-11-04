<?php

namespace App\Http\Controllers\v1\management\billing\options;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\Options\ActivitiesRequest;
use App\Http\Resources\v1\management\billing\options\ActivitiesResource;
use App\Services\v1\management\billing\options\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Billing\Options\ActivityModel;
use App\Services\v1\management\DataViewerService;

class ActivitiesController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = ActivityModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(ActivitiesRequest $request, ActivityService $service): JsonResponse
    {
        $activity = $service->createActivity($request->toDTO());

        return response()->json([
            'saved' => (bool)$activity,
            'activity' => new ActivitiesResource($activity),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'activity' => ActivityModel::query()->findOrFail($request->input('id')),
        ]);
    }

    public function update(ActivitiesRequest $request, ActivityModel $id, ActivityService $service): JsonResponse
    {
        $activity = $service->updateActivity($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$activity,
            'activity' => new ActivitiesResource($activity),
        ]);
    }
}
