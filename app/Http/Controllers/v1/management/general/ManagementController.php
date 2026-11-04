<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Management\PermissionModel;
use App\Models\Management\RoleModel;
use App\Models\Management\TechnicianModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{
    public function rolesList(): JsonResponse
    {
        $user = Auth::user()->load('roles');
        $role = $user->roles->first()?->id;
        $query = RoleModel::query()->select('id', 'name');

        if ($role !== 1) {
            $query->where('id', '!=', 1);
        }

        return response()->json([
            'response' => $query->get()
        ]);
    }

    public function permissionsByRoleId(int $roleID): JsonResponse
    {
        $role = RoleModel::query()->with('permissions')->find($roleID);
        return response()->json([
            'response' => $role->permissions()->pluck('id')->toArray()
        ]);
    }

    public function usersList(): JsonResponse
    {
        return response()->json([
            'response' => User::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function technicianList(): JsonResponse
    {
        $query = TechnicianModel::query()
            ->with('user:id,name')
            ->whereHas('user', fn($q) => $q->where('status_id', CommonStatus::ACTIVE->value))
            ->where('status_id', 1)
            ->get();
        $data = [];

        foreach ($query as $item) {
            $el = [
                'id' => $item->id,
                'name' => $item->user?->name,
            ];
            $data[] = $el;
        }

        return response()->json(['response' => $data]);
    }

    public function permissionsList(): JsonResponse
    {
        $permissions = PermissionModel::query()
            ->selectRaw("split_part(name, '.', 1) as category, id as value, name as label")
            ->orderBy('category', 'ASC')
            ->orderBy('label', 'ASC')
            ->get()
            ->groupBy('category')
            ->map(function ($items, $category) {
                return [
                    'category' => ucfirst($category),
                    'permissions' => $items->map(function ($item) {
                        return [
                            'value' => $item->value,
                            'label' => $item->label,
                        ];
                    })
                ];
            })
            ->values();

        return response()->json([
            'response' => $permissions
        ]);
    }
}
