<?php

namespace App\Http\Controllers\v1\management\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\v1\management\admin\users\PermissionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Admin\Users\PermissionsRequest;
use App\Models\Management\PermissionModel;
use App\Http\Resources\v1\management\admin\users\PermissionsResource;

class PermissionsController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = PermissionModel::query()
            ->with('menu')
            ->orderBy('name');

        return response()->json([
            'collection' => $query->advancedFilter()
        ]);
    }

    public function store(PermissionsRequest $request, PermissionsService $service): JsonResponse
    {
        $permission = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$permission,
            'permission' => new PermissionsResource($permission)
        ]);
    }

    public function edit(Request $request, PermissionsService $service): JsonResponse
    {
        return response()->json([
            'permission' => new PermissionsResource($service->read($request->input('id')))
        ]);
    }

    public function update(PermissionsRequest $request, PermissionModel $id, PermissionsService $service): JsonResponse
    {
        $permission = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$permission,
            'permission' => new PermissionsResource($permission)
        ]);
    }
}
