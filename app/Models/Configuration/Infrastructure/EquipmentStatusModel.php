<?php

namespace App\Models\Configuration\Infrastructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $badge_color
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $description
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentStatusModel withoutTrashed()
 * @mixin \Eloquent
 */
class EquipmentStatusModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_infrastructure_equipment_status';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description', 'badge_color', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
