<?php

namespace App\Http\Controllers\v1\management\clients;

use App\Http\Controllers\Controller;
use App\Services\v1\management\client\ReferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Clients\ReferencesRequest;
use App\Models\Clients\ReferenceModel;
use App\Http\Resources\v1\management\clients\ReferencesResource;

class ReferencesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'response' => ReferenceModel::query()
                ->with('kinship')
                ->where('client_id', $request->input('clientID'))
                ->get()
        ]);
    }

    public function store(ReferencesRequest $request, ReferenceService $service): JsonResponse
    {
        $reference = $service->createReference($request->toDTO());

        return response()->json([
            'saved' => (boolean)$reference,
            'reference' => new ReferencesResource($reference)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['reference' => ReferenceModel::query()->find($request->input('id'))]);
    }

    public function update(ReferencesRequest $request, ReferenceModel $id, ReferenceService $service): JsonResponse
    {
        $reference = $service->updateReference($id, $request->toDTO());

        return response()->json([
            'saved' => (boolean)$reference,
            'reference' => new ReferencesResource($reference)
        ]);
    }
}
