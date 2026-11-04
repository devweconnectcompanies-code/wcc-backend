<?php

namespace App\Services\v1\management\admin\users;

use App\DTOs\v1\management\admin\users\RoleDTO;
use App\Models\Management\RoleModel;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Permission;

class RoleService
{
    public function create(RoleDTO $DTO): RoleModel
    {
        $role = RoleModel::query()->create($DTO->except('permissions')->toArray());

        if (!empty($DTO->permissions)) {
            $this->validatePermissions($DTO->permissions);
            $this->syncRolePermissions($role, $DTO->permissions);
        }
        return $role->refresh();
    }

    public function read(int $id): array
    {
        $role = RoleModel::query()->find($id);
        $permissions = $role->permissions();
        return [
            'role' => $role,
            'permissions' => $permissions->pluck('id')->toArray(),
        ];
    }

    public function update(RoleModel $roleModel, RoleDTO $DTO): RoleModel
    {
        if (!isset($DTO->permissions)) {
            throw new InvalidArgumentException('El campo permissions es obligatorio');
        }
        $roleModel->update($DTO->except('permissions')->toArray());
        $this->validatePermissions($DTO->permissions);
        $this->syncRolePermissions($roleModel, $DTO->permissions);
        return $roleModel->refresh();
    }

    private function syncRolePermissions(RoleModel $roleModel, array $permissionIDs): void
    {
        $permissionNames = Permission::query()
            ->whereIn('id', $permissionIDs)
            ->pluck('name');

        $roleModel->syncPermissions($permissionNames);
    }

    private function validatePermissions(array $permissionIDs): void
    {
        $existingCount = Permission::query()
            ->whereIn('id', $permissionIDs)
            ->count();

        if ($existingCount !== count($permissionIDs)) {
            throw new ModelNotFoundException('Uno o más permisos no existen');
        }
    }
}
