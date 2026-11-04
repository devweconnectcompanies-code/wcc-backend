<?php

namespace App\Http\Controllers\v1\management\billing\options;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\Options\DiscountRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Billing\DiscountModel;
use App\Services\v1\management\billing\options\DiscountService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = DiscountModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(DiscountRequest $request, DiscountService $service): JsonResponse
    {
        $discount = $service->createDiscount($request->toDTO());

        return response()->json([
            'saved' => (bool)$discount,
            'discount' => new GeneralResource($discount),
        ]);
    }

    public function edit(Request $request, DiscountService $service): JsonResponse
    {
        $discount = $service->editDiscount($request->input('id'));

        return response()->json([
            'discount' => new GeneralResource($discount),
        ]);
    }

    public function update(DiscountRequest $request, DiscountModel $id, DiscountService $service): JsonResponse
    {
        $discount = $service->updateDiscount($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$discount,
            'discount' => new GeneralResource($discount),
        ]);
    }
}
