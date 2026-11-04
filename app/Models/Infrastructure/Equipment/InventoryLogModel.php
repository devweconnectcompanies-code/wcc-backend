<?php

namespace App\Models\Infrastructure\Equipment;

use App\Models\Configuration\Infrastructure\EquipmentStatusModel;
use App\Models\Management\TechnicianModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;

/**
 * @property int $id
 * @property int $equipment_id
 * @property int $user_id
 * @property int|null $technician_id
 * @property string $execution_date
 * @property int|null $service_id
 * @property string $description
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Infrastructure\Equipment\InventoryModel|null $equipment
 * @property-read ServiceModel|null $service
 * @property-read EquipmentStatusModel|null $status
 * @property-read TechnicianModel|null $technician
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereExecutionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryLogModel withoutTrashed()
 * @mixin \Eloquent
 */
class InventoryLogModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_inventory_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'equipment_id',
        'user_id',
        'technician_id',
        'execution_date',
        'service_id',
        'status_id',
        'description'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'equipment_id',
        'user_id',
        'technician_id',
        'service_id',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'equipment_id',
        'user_id',
        'technician_id',
        'service_id',
        'status_id',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(InventoryModel::class, 'equipment_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(TechnicianModel::class, 'technician_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(EquipmentStatusModel::class, 'status_id', 'id');
    }
}
