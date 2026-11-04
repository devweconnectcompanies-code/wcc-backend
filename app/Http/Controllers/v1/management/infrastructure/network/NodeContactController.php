<?php

namespace App\Http\Controllers\v1\management\infrastructure\network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\v1\Management\Infrastructure\Network\NodeContactRequest;
use App\Http\Resources\v1\management\infrastructure\network\NodeContactResource;
use App\Models\Infrastructure\Network\NodeContactModel;
use App\Services\v1\management\infrastructure\network\NodeContactService;

class NodeContactController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'response' => NodeContactModel::query()
                ->where('node_id', $request->input('node'))
                ->get()
        ]);
    }

    public function store(NodeContactRequest $request, NodeContactService $nodeContactService): JsonResponse
    {
        $contact = $nodeContactService->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$contact,
            'contact' => new NodeContactResource($contact)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['contact' => NodeContactModel::query()->find($request->input('id'))]);
    }

    public function update(NodeContactRequest $request, NodeContactModel $id, NodeContactService $nodeContactService): JsonResponse
    {
        $contact = $nodeContactService->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$contact,
            'contact' => new NodeContactResource($contact)
        ]);
    }
}
