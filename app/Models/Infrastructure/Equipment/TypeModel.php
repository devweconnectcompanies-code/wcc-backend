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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeModel withoutTrashed()
 * @mixin \Eloquent
 */
class TypeModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_equipment_types';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
