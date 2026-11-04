<?php

namespace App\Http\Controllers\v1\management\infrastructure\network;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Infrastructure\Network\AuthServersRequest;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Http\Resources\v1\management\infrastructure\network\AuthServerResource;
use App\Services\v1\management\infrastructure\network\AuthServerService;
use App\Services\v1\management\DataViewerService;

class AuthServersController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = AuthServerModel::query();

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
        ]);
    }

    public function store(AuthServersRequest $request, AuthServerService $service): JsonResponse
    {
        $server = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$server,
            'server' => new AuthServerResource($server)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['server' => AuthServerModel::query()->find($request->input('id'))]);
    }

    public function update(AuthServersRequest $request, AuthServerModel $id, AuthServerService $service): JsonResponse
    {
        $server = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$server,
            'server' => new AuthServerResource($server)
        ]);
    }
}
