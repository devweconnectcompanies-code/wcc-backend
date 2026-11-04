<?php

namespace App\Models\Configuration;

use App\Models\Management\PermissionModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property string|null $url
 * @property string|null $icon
 * @property int|null $parent_id
 * @property int $order
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MenuModel> $children
 * @property-read int|null $children_count
 * @property-read array $status
 * @property-read MenuModel|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PermissionModel> $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuModel withoutTrashed()
 * @mixin \Eloquent
 */
class MenuModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_menu';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'url', 'slug', 'icon', 'parent_id', 'order', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'url', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuModel::class, 'parent_id')
            ->where('status_id', 1);
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuModel::class, 'parent_id')
            ->where('status_id', 1)
            ->orderBy('order');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(PermissionModel::class, 'menu_id', 'id');
    }
}
