<?php

namespace App\Models\Infrastructure\Equipment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property int $equipment_type_id
 * @property int $brand_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Infrastructure\Equipment\BrandModel|null $brand
 * @property-read array $status
 * @property-read \App\Models\Infrastructure\Equipment\TypeModel|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereEquipmentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelModel withoutTrashed()
 * @mixin \Eloquent
 */
class ModelModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_equipment_models';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'equipment_type_id', 'brand_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'equipment_type_id', 'brand_id', 'status_id'];
    protected array $orderable = ['id', 'name', 'equipment_type_id', 'brand_id', 'status_id'];
    protected $appends = ['status'];

    public function brand(): HasOne
    {
        return $this->hasOne(BrandModel::class, 'id', 'brand_id')
            ->where('status_id', 1);
    }

    public function type(): HasOne
    {
        return $this->hasOne(TypeModel::class, 'id', 'equipment_type_id')
            ->where('status_id', 1);
    }
}
