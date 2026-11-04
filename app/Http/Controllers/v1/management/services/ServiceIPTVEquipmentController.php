<?php

namespace App\Http\Controllers\v1\management\services;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Services\ServiceIPTVEquipmentRequest;
use App\Models\Services\ServiceIptvEquipmentModel;
use App\Services\v1\management\services\IPTVEquipmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\v1\management\services\ServiceIptvEquipmentResource;

class ServiceIPTVEquipmentController extends Controller
{
    public function data(Request $request, IPTVEquipmentService $service): JsonResponse
    {
        $item = $service->list((int)$request->input('service'));
        $plan = $service->internet_plan((int)$request->input('service'));

        return response()->json([
            'collection' => new ServiceIptvEquipmentResource($item),
            'has_iptv' => $plan->iptv,
            'allowed_devices' => $plan->allowed_stb,
        ]);
    }

    public function store(ServiceIptvEquipmentRequest $request, IPTVEquipmentService $service): JsonResponse
    {
        $item = $service->assign($request->toDTO());

        return response()->json([
            'saved' => (bool)$item,
            'equipment' => new ServiceIptvEquipmentResource($item->load('equipment'))
        ]);
    }

    public function edit(Request $request, IPTVEquipmentService $service): JsonResponse
    {
        $item = $service->read($request->input('id'));

        return response()->json([
            'equipment' => new ServiceIptvEquipmentResource($item)
        ]);
    }

    public function update(ServiceIptvEquipmentRequest $request, ServiceIptvEquipmentModel $id, IPTVEquipmentService $service): JsonResponse
    {
        $item = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$item,
            'equipment' => new ServiceIptvEquipmentResource($item->load('equipment'))
        ]);
    }

    public function remove(Request $request, IPTVEquipmentService $service): JsonResponse
    {
        $item = $service->delete($request->input('id'));

        return response()->json([
            'deleted' => (bool)$item,
        ]);
    }

    public function suggestedEmail(IPTVEquipmentService $service): JsonResponse
    {
        $suggestedEmail = $service->getSuggestedEmail();

        return response()->json([
            'suggested_email' => $suggestedEmail,
        ]);
    }
}
