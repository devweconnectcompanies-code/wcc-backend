<?php

namespace App\Models\Services\Logs;

use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Models\Services\ServiceSoldDeviceModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $service_sold_device_id
 * @property int $service_id
 * @property int $equipment_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read InventoryModel|null $equipment
 * @property-read ServiceModel|null $service
 * @property-read ServiceSoldDeviceModel|null $sold_device
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereServiceSoldDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceSoldDeviceLog whereUserId($value)
 * @mixin \Eloquent
 */
class ServiceSoldDeviceLog extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'services_sold_devices_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_sold_device_id',
        'service_id',
        'equipment_id',
        'user_id',
        'action',
        'before',
        'after',
    ];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function sold_device(): BelongsTo
    {
        return $this->belongsTo(ServiceSoldDeviceModel::class, 'service_sold_device_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(InventoryModel::class, 'equipment_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
