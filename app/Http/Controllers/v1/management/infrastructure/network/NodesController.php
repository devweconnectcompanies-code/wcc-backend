<?php

namespace App\Http\Controllers\v1\management\infrastructure\network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\v1\Management\Infrastructure\Network\NodesRequest;
use App\Http\Resources\v1\management\infrastructure\network\NodeResource;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\infrastructure\network\NodeService;
use App\Models\Infrastructure\Network\NodeModel;

class NodesController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = NodeModel::query()->with(['auth_server', 'state', 'municipality', 'district']);
        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
        ]);
    }

    public function store(NodesRequest $request, NodeService $service): JsonResponse
    {
        $node = $service->create($request->toDTO());
        return response()->json([
            'saved' => (bool)$node,
            'node' => new NodeResource($node)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['node' => NodeModel::query()->find($request->input('id'))]);
    }

    public function update(NodesRequest $request, NodeModel $id, NodeService $service): JsonResponse
    {
        $node = $service->update($id, $request->toDTO());
        return response()->json([
            'saved' => (bool)$node,
            'node' => new NodeResource($node)
        ]);
    }
}
