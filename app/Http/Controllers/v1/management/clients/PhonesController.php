<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Clients\PhoneRequest;
use App\Http\Resources\v1\management\clients\PhoneResource;
use App\Models\Clients\PhoneModel;
use App\Services\v1\management\client\PhoneService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhonesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = PhoneModel::query()->with(['phone_type', 'country'])
            ->where('client_id', $request->input('clientID'))
            ->get();

        return response()->json(['response' => $query]);
    }

    public function store(PhoneRequest $request, PhoneService $phoneService): JsonResponse
    {
        $phone = $phoneService->storePhone($request->toDTO());

        return response()->json([
            'saved' => (bool)$phone,
            'phone' => new PhoneResource($phone)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['phone' => PhoneModel::query()
            ->with(['phone_type', 'country'])
            ->where('id', $request->input('phoneID'))
            ->firstOrFail()
        ]);
    }

    public function update(PhoneRequest $request, PhoneModel $id, PhoneService $phoneService): JsonResponse
    {
        $phone = $phoneService->updatePhone($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$phone,
            'phone' => new PhoneResource($phone)
        ]);
    }
}
