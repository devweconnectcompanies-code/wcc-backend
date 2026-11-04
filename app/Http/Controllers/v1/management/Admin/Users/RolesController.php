<?php

namespace App\Http\Controllers\v1\management\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Admin\Users\RolesRequest;
use App\Http\Resources\v1\management\admin\users\RolesResource;
use App\Models\Management\RoleModel;
use App\Services\v1\management\admin\users\RoleService;
use Illuminate\Http\JsonResponse;

class RolesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = RoleModel::query()->orderBy('name');

        return response()->json([
            'collection' => $query->advancedFilter()
        ]);
    }

    public function store(RolesRequest $request, RoleService $service): JsonResponse
    {
        $role = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$role,
            'role' => new RolesResource($role)
        ]);
    }

    public function edit(Request $request, RoleService $service): JsonResponse
    {
        return response()->json([
            'role' => new RolesResource($service->read($request->input('id')))
        ]);
    }

    public function update(RolesRequest $request, RoleModel $id, RoleService $service): JsonResponse
    {
        $role = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$role,
            'role' => new RolesResource($role)
        ]);
    }
}
