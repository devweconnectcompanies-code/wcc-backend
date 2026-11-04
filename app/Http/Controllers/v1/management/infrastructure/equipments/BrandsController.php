<?php

namespace App\Http\Controllers\v1\management\infrastructure\equipments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Infrastructure\Equipments\BrandRequest;
use App\Services\v1\management\infrastructure\equipments\BrandService;
use App\Services\v1\management\DataViewerService;
use App\Http\Resources\v1\management\infrastructure\equipments\BrandResource;
use App\Models\Infrastructure\Equipment\BrandModel;

class BrandsController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = BrandModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
        ]);
    }

    public function store(BrandRequest $request, BrandService $service): JsonResponse
    {
        $brand = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$brand,
            'brand' => new BrandResource($brand)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'brand' => BrandModel::query()->find($request->input('id')),
        ]);
    }

    public function update(BrandRequest $request, BrandModel $id, BrandService $service): JsonResponse
    {
        $brand = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$brand,
            'brand' => new BrandResource($brand)
        ]);
    }
}
