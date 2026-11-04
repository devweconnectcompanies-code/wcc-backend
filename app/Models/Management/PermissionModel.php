<?php

namespace App\Models\Management;

use App\Models\Configuration\MenuModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission;
use App\Traits\DataViewer;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $menu_id
 * @property-read MenuModel|null $menu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PermissionModel withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class PermissionModel extends Permission
{
    use DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'guard_name', 'menu_id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected array $allowedFilters = ['id', 'name', 'menu_id'];
    protected array $orderable = ['id', 'name', 'menu_id'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(MenuModel::class, 'menu_id', 'id');
    }
}
