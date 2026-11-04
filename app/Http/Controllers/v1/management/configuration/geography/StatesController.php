<?php

namespace App\Http\Controllers\v1\management\configuration\geography;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\StatesRequest;
use App\Models\Configuration\Geography\StateModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = StateModel::query();
        $statusFilter = collect($request->e ?? [])->firstWhere('column', 'status');
        if ($statusFilter) {
            $query->whereIn('status_id', json_decode($statusFilter['data']));
        }
        $query = $query->orderBy('status_id', 'desc')->advancedFilter();

        return response()->json(['collection' => $query]);
    }

    public function create(StatesRequest $request): JsonResponse
    {
        $state = StateModel::create([
            'name' => $request->name,
            'code' => $request->code,
            'iso_code' => $request->iso,
            'status_id' => $request->status
        ]);

        return response()->json(['saved' => (bool)$state, 'state' => $state]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['state' => StateModel::query()->find($request->id ?? 0)]);
    }

    public function update(StatesRequest $request, $id): JsonResponse
    {
        $state = StateModel::query()->findOrFail($id);
        $state->update([
            'name' => $request->name,
            'code' => $request->code,
            'iso_code' => $request->iso,
            'status_id' => $request->status
        ]);

        return response()->json(['saved' => (bool)$state, 'state' => $state]);
    }
}
