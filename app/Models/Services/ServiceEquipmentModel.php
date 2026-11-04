<?php

namespace App\Models\Services;

use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Observers\Services\ServiceEquipmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

#[ObservedBy(ServiceEquipmentObserver::class)]
/**
 * @property int $id
 * @property int $equipment_id
 * @property int $service_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read InventoryModel|null $equipment
 * @property-read array $status
 * @property-read \App\Models\Services\ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentModel withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceEquipmentModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'services_equipment';
    protected $primaryKey = 'id';
    protected $fillable = ['equipment_id', 'service_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'equipment_id', 'service_id', 'status_id'];
    protected array $orderable = ['id', 'equipment_id', 'service_id', 'status_id'];
    protected $appends = ['status'];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(InventoryModel::class, 'equipment_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }
}
