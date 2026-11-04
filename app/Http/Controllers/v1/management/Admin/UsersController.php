<?php

namespace App\Http\Controllers\v1\management\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Admin\UsersRequest;
use App\Models\User;
use App\Services\v1\management\admin\users\UserService;
use App\Services\v1\management\DataViewerService;
use App\Http\Resources\v1\management\admin\users\UserResource;

class UsersController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = User::query()->with('roles');

        return $service->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'role' => fn($q, $data) => $q->whereHas('roles', function ($x) use ($data) {
                return $x->whereIn('id', $data);
            }),
        ]);
    }

    public function store(UsersRequest $request, UserService $service): JsonResponse
    {
        $user = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$user,
            'user' => new UserResource($user)
        ]);
    }

    public function edit(Request $request, UserService $service): JsonResponse
    {
        $user = $service->read($request->input('id'));

        return response()->json(new UserResource($user));
    }

    public function update(UsersRequest $request, User $id, UserService $service): JsonResponse
    {
        $user = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$user,
            'user' => new UserResource($user)
        ]);
    }
}
