<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\KinshipRequest;
use App\Http\Resources\v1\management\configuration\clients\KinshipResource;
use App\Services\v1\management\configuration\clients\KinshipService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Configuration\Clients\KinshipModel;
use App\Services\v1\management\DataViewerService;

class KinshipController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = KinshipModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(KinshipRequest $request, KinshipService $service): JsonResponse
    {
        $kinship = $service->createKinship($request->toDTO());

        return response()->json([
            'saved' => (bool)$kinship,
            'kinship' => new KinshipResource($kinship)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['kinship' => KinshipModel::query()->findOrFail($request->id)]);
    }

    public function update(KinshipRequest $request, KinshipModel $id, KinshipService $service): JsonResponse
    {
        $kinship = $service->updateKinship($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$kinship,
            'kinship' => new KinshipResource($kinship)
        ]);
    }
}
