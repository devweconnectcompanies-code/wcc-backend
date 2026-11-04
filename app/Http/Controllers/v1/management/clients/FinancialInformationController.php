<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Clients\FinancialInformationRequest;
use App\Http\Resources\v1\management\clients\FinancialInformationResource;
use App\Models\Clients\FinancialInformationModel;
use App\Services\v1\management\client\FinancialInformationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinancialInformationController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'response' => FinancialInformationModel::query()
                ->where('client_id', $request->get('clientID'))
                ->first()
        ]);
    }

    public function store(FinancialInformationRequest $request, FinancialInformationService $service): JsonResponse
    {
        $info = $service->createInformation($request->toDTO());

        return response()->json([
            'saved' => (bool)$info,
            'info' => new FinancialInformationResource($info),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'info' => FinancialInformationModel::query()
                ->findOrFail($request->get('id'))
        ]);
    }

    public function update(FinancialInformationRequest $request, FinancialInformationModel $id, FinancialInformationService $service): JsonResponse
    {
        $info = $service->updateInformation($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$info,
            'info' => new FinancialInformationResource($info),
        ]);
    }
}
