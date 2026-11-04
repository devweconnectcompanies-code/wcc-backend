<?php

namespace App\Models\Services;

use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Observers\Services\ServiceSoldDeviceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ServiceSoldDeviceObserver::class)]
/**
 * @property int $id
 * @property int $equipment_id
 * @property int $service_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read InventoryModel|null $equipment
 * @property-read \App\Models\Services\ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceModel withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceSoldDeviceModel extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'services_sold_devices';
    protected $primaryKey = 'id';
    protected $fillable = ['equipment_id', 'service_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(InventoryModel::class, 'equipment_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }
}
