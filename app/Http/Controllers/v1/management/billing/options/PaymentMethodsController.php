<?php

namespace App\Http\Controllers\v1\management\billing\options;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\Options\PaymentMethodsRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Billing\Options\PaymentMethodModel;
use App\Services\v1\management\billing\options\PaymentMethodService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $query = PaymentMethodModel::query();

        return $dataViewer->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(PaymentMethodsRequest $request, PaymentMethodService $service): JsonResponse
    {
        $method = $service->storeMethod($request->toDTO());
        return response()->json([
            'saved' => $method,
            'method' => new GeneralResource($method),
        ]);
    }

    public function edit(Request $request, PaymentMethodService $service): JsonResponse
    {
        $method = $service->editMethod($request->input('id'));
        return response()->json([
            'method' => new GeneralResource($method),
        ]);
    }

    public function update(PaymentMethodsRequest $request, PaymentMethodModel $id, PaymentMethodService $service): JsonResponse
    {
        $method = $service->updateMethod($id, $request->toDTO());
        return response()->json([
            'saved' => $method,
            'method' => new GeneralResource($method),
        ]);
    }
}
