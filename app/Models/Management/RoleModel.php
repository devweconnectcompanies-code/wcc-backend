<?php

namespace App\Models\Management;

use App\Traits\DataViewer;
use Spatie\Permission\Models\Role;


/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $homepage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel whereHomepage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleModel withoutPermission($permissions)
 * @mixin \Eloquent
 */
class RoleModel extends Role
{
    use DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'guard_name', 'homepage'];
    protected $hidden = ['created_at', 'updated_at'];
    protected array $allowedFilters = ['id', 'name'];
    protected array $orderable = ['id', 'name'];
}
