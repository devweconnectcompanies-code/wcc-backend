<?php

namespace App\Services\v1\management\admin\users;

use App\DTOs\v1\management\admin\users\UserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserService
{
    public function create(UserDTO $DTO): User
    {
        if (!isset($DTO->role)) {
            throw new \InvalidArgumentException('No se ha seleccionado ningun rol.');
        }

        if (!empty($DTO->permissions)) {
            $this->validatePermissions($DTO->permissions);
        }

        $user = User::query()->create([
            'name' => $DTO->name,
            'email' => $DTO->email,
            'password' => bcrypt($DTO->password),
            'status_id' => $DTO->status_id,
        ]);

        $this->syncRole($user, $DTO->role);

        if (!empty($DTO->permissions)) {
            $this->syncUserPermissions($user, $DTO->permissions);
        }

        return $user->refresh();
    }

    public function read(int $id): array
    {
        $user = User::query()
            ->with(['roles.permissions', 'permissions'])
            ->findOrFail($id);
        $permissions = $user->getAllPermissions()
            ->pluck('id')
            ->unique()
            ->values();

        return [
            'user' => $user,
            'permissions' => $permissions,
        ];
    }

    public function update(User $model, UserDTO $DTO): User
    {
        $user = User::query()->findOrFail($model->id);
        $user->name = $DTO->name;
        $user->email = $DTO->email;
        $user->status_id = $DTO->status_id;
        if (!is_null($DTO->password) && trim($DTO->password) !== '') {
            $user->password = bcrypt($DTO->password);

            if (Auth::id() === $user->id) {
                session()->put('password_hash' . Auth::getDefaultDriver(), $user->getAuthPassword());
            }
        }
        $user->save();

        if (!isset($DTO->role)) {
            throw new \InvalidArgumentException('No se ha seleccionado ningun rol.');
        }
        $this->syncRole($user, $DTO->role);

        if (!empty($DTO->permissions)) {
            $this->validatePermissions($DTO->permissions);
            $this->syncUserPermissions($user, $DTO->permissions);
        } else {
            $user->syncRoles([]);
        }

        return $user->refresh();
    }

    private function syncRole(User $model, int $id): void
    {
        $role = Role::query()->findOrFail($id);
        $model->syncRoles([$role->name]);
    }

    private function validatePermissions(array $permissions): void
    {
        $count = Permission::query()
            ->whereIn('id', $permissions)
            ->count();

        if ($count !== count($permissions)) {
            throw new ModelNotFoundException('Uno o más permisos no existen.');
        }
    }

    private function syncUserPermissions(User $user, array $permissions): void
    {
        $permissionsNames = Permission::query()
            ->whereIn('id', $permissions)
            ->pluck('name');
        $user->syncPermissions($permissionsNames);
    }
}
