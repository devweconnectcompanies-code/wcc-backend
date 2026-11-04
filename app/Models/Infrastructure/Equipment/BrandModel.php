<?php

namespace App\Models\Infrastructure\Equipment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BrandModel withoutTrashed()
 * @mixin \Eloquent
 */
class BrandModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_equipment_brands';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
