<?php

namespace App\Services\v1\management\admin\users;

use App\Models\Management\PermissionModel;
use App\DTOs\v1\management\admin\users\PermissionDTO;

class PermissionsService
{
    public function create(PermissionDTO $permissionDTO): PermissionModel
    {
        return PermissionModel::query()->create($permissionDTO->toArray());
    }

    public function read(int $id): PermissionModel
    {
        return PermissionModel::query()->find($id)->load('menu');
    }

    public function update(PermissionModel $permissionModel, PermissionDTO $permissionDTO): PermissionModel
    {
        $permissionModel->update($permissionDTO->toArray());
        return $permissionModel;
    }
}
