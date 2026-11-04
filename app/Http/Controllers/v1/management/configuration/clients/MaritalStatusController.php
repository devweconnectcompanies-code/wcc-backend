<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Configuration\MaritalStatusRequest;
use App\Models\Configuration\Clients\MaritalStatusModel;
use App\Http\Resources\v1\management\configuration\clients\MaritalStatusResource;
use App\Services\v1\management\configuration\clients\MaritalStatusService;

class MaritalStatusController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = MaritalStatusModel::query();
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

    public function store(MaritalStatusRequest $request, MaritalStatusService $maritalStatusService): JsonResponse
    {
        $marital = $maritalStatusService->createMaritalStatus($request->toDTO());

        return response()->json([
            'saved' => (bool)$marital,
            'status' => new MaritalStatusResource($marital)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['status' => MaritalStatusModel::query()->findOrFail($request->id)]);
    }

    public function update(MaritalStatusRequest $request, MaritalStatusModel $id, MaritalStatusService $maritalStatusService): JsonResponse
    {
        $marital = $maritalStatusService->updateMaritalStatus($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$marital,
            'status' => new MaritalStatusResource($marital)
        ]);
    }

}
