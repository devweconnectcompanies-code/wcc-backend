<?php

namespace App\Http\Controllers\v1\management\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Services\ServiceEquipmentRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\v1\management\services\ServiceEquipmetResource;
use App\Services\v1\management\services\EquipmentService;

class ServiceEquipmentController extends Controller
{
    public function list(Request $request, EquipmentService $service): JsonResponse
    {
        $equipment = $service->serviceEquipment($request->input('service'));

        return response()->json([
            'equipment' => new ServiceEquipmetResource($equipment),
        ]);
    }

    public function store(ServiceEquipmentRequest $request, EquipmentService $service): JsonResponse
    {
        $equipment = $service->assignEquipment($request->toDTO());

        return response()->json([
            'saved' => (bool)$equipment,
            'equipment' => new ServiceEquipmetResource($equipment),
        ]);
    }

    public function remove(Request $request, EquipmentService $service): JsonResponse
    {
        $query = $service->removeEquipment($request->input('id'));

        return response()->json([
            'deleted' => (bool)$query,
        ]);
    }
}
