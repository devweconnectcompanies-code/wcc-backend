<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\configuration\clients\ClientTypeResource;
use App\Services\v1\management\configuration\clients\ClientTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Configuration\ClientTypeRequest;
use App\Models\Configuration\Clients\ClientTypeModel;

class ClientTypeController extends Controller
{
    public function dataviewer(Request $request): JsonResponse
    {
        $query = ClientTypeModel::query();
        if ($request->has('e')) {
            foreach ($request->e as $filter) {
                if (!isset($filter['column'], $filter['data'])) continue;
                $data = json_decode($filter['data'], true);
                if (!is_array($data)) continue;
                match ($filter['column']) {
                    default => null,
                    'status' => $query->whereIn('status_id', $data),
                };
            }
        }

        $query = $query->orderByDesc('status_id')->advancedFilter();

        return response()->json(['collection' => $query]);
    }

    public function store(ClientTypeRequest $request, ClientTypeService $clientTypeService): JsonResponse
    {
        $client = $clientTypeService->createType($request->toDTO());

        return response()->json([
            'saved' => (bool)$client,
            'type' => new ClientTypeResource($client)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['type' => ClientTypeModel::query()->findOrFail($request->id)]);
    }

    public function update(ClientTypeRequest $request, ClientTypeModel $id, ClientTypeService $clientTypeService): JsonResponse
    {
        $update = $clientTypeService->updateType($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$update,
            'type' => new ClientTypeResource($update)
        ]);
    }
}
