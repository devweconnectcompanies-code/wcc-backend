<?php

namespace App\Http\Controllers\v1\management\services;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Services\ServiceInternetRequest;
use App\Http\Resources\v1\management\services\ServiceInternetResource;
use App\Models\Services\ServiceInternetModel;
use App\Services\v1\management\services\InternetService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceInternetsController extends Controller
{
    public function store(ServiceInternetRequest $request, InternetService $service): JsonResponse
    {
        $internet = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$internet,
            'internet' => new ServiceInternetResource($internet)
        ]);
    }

    public function read(Request $request, InternetService $service): JsonResponse
    {
        $internet = $service->read($request->input('service'));

        return response()->json([
            'can_create' => $internet === null,
            'internet' => $internet ? new ServiceInternetResource($internet) : null
        ]);
    }

    public function update(ServiceInternetRequest $request, ServiceInternetModel $id, InternetService $service): JsonResponse
    {
        $internet = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$internet,
            'internet' => new ServiceInternetResource($internet)
        ]);
    }
}
