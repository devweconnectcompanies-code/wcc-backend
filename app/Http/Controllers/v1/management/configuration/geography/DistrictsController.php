<?php

namespace App\Http\Controllers\v1\management\configuration\geography;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\DistrictsRequest;
use App\Http\Resources\v1\management\configuration\geography\DistrictResource;
use App\Models\Configuration\Geography\DistrictModel;
use App\Services\v1\management\configuration\geography\DistrictService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\v1\management\DataViewerService;

class DistrictsController extends Controller
{
    public function data(Request $request, DataviewerService $dataViewerService): JsonResponse
    {
        $query = DistrictModel::query()->with(['municipality', 'state']);

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'state' => fn($q, $data) => $q->whereIn('state_id', $data),
            'municipality' => fn($q, $data) => $q->whereIn('municipality_id', $data),
        ]);
    }


    public function store(DistrictsRequest $request, DistrictService $districtService): JsonResponse
    {
        $district = $districtService->createDistrict($request->toDto());

        return response()->json([
            'saved' => (bool)$district,
            'district' => new DistrictResource($district)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['district' => DistrictModel::query()->findOrFail($request->id)]);
    }

    public function update(DistrictsRequest $request, DistrictModel $id, DistrictService $districtService): JsonResponse
    {
        $district = $districtService->updateDistrict($id, $request->toDto());
        return response()->json([
            'saved' => (bool)$district,
            'district' => new DistrictResource($district)
        ]);
    }
}
