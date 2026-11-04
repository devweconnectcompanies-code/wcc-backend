<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Clients\AddressRequest;
use App\Http\Resources\v1\management\clients\AddressResource;
use App\Models\Clients\AddressModel;
use App\Services\v1\management\client\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'response' => AddressModel::query()
                ->with(['state', 'municipality', 'district'])
                ->where('client_id', $request->input('clientID'))
                ->get()
        ]);
    }

    public function store(AddressRequest $request, AddressService $service): JsonResponse
    {
        $address = $service->createAddress($request->toDTO());

        return response()->json([
            'saved' => (bool)$address,
            'address' => new AddressResource($address)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'address' => AddressModel::query()->findOrFail($request->input('id')),
        ]);
    }

    public function update(AddressRequest $request, AddressModel $id, AddressService $service): JsonResponse
    {
        $address = $service->updateAddress($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$address,
            'address' => new AddressResource($address)
        ]);
    }
}
