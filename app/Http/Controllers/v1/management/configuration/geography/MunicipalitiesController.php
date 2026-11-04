<?php

namespace App\Http\Controllers\v1\management\configuration\geography;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\MunicipalitiesRequest;
use App\Models\Configuration\Geography\MunicipalityModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MunicipalitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data(Request $request): JsonResponse
    {
        $query = MunicipalityModel::query()->with('state');

        if ($request->has('e')) {
            foreach ($request->e as $filter) {
                if (!isset($filter['column'], $filter['data'])) {
                    continue;
                }
                $data = json_decode($filter['data'], true);

                if (!is_array($data)) {
                    continue;
                }

                match ($filter['column']) {
                    'status' => $query->whereIn('status_id', $data),
                    'state' => $query->whereIn('state_id', $data),
                    default => null,
                };
            }
        }

        $query = $query->orderByDesc('status_id')
            ->advancedFilter();

        return response()->json(['collection' => $query]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MunicipalitiesRequest $request): JsonResponse
    {
        $municipality = MunicipalityModel::create([
            'name' => $request->name,
            'code' => $request->code,
            'state_id' => $request->state,
            'status_id' => $request->status
        ]);
        return response()->json(['saved' => (bool)$municipality, 'municipality' => $municipality]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): JsonResponse
    {
        return response()->json(['municipality' => MunicipalityModel::query()->findOrFail($request->id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MunicipalitiesRequest $request, string $id)
    {
        $municipality = MunicipalityModel::query()->findOrFail($id);
        $municipality->update([
            'name' => $request->name,
            'code' => $request->code,
            'state_id' => $request->state,
            'status_id' => $request->status
        ]);
        return response()->json(['saved' => (bool)$municipality, 'municipality' => $municipality]);
    }
}
