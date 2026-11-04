<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use App\Services\v1\management\configuration\clients\ContractStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Configuration\Clients\ContractStateModel;
use App\Services\v1\management\DataViewerService;
use App\Http\Requests\v1\Management\Configuration\ContractStatusRequest;
use App\Http\Resources\v1\management\configuration\clients\ContractStatusResource;

class ContractsStatusController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = ContractStateModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(ContractStatusRequest $request, ContractStatusService $service): JsonResponse
    {
        $status = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new ContractStatusResource($status),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'status' => ContractStateModel::query()->find($request->get('id'))
        ]);
    }

    public function update(ContractStatusRequest $request, ContractStateModel $id, ContractStatusService $service): JsonResponse
    {
        $status = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$status,
            'status' => new ContractStatusResource($status),
        ]);
    }
}
