<?php

namespace App\Models\Services\Logs;

use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Models\Services\ServiceIptvEquipmentModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $service_iptv_equipment_id
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
 * @property-read ServiceIptvEquipmentModel|null $service_iptv
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereServiceIptvEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIPTVEquipmentLog whereUserId($value)
 * @mixin \Eloquent
 */
class ServiceIPTVEquipmentLog extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'services_iptv_equipment_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_iptv_equipment_id',
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

    public function service_iptv(): BelongsTo
    {
        return $this->belongsTo(ServiceIptvEquipmentModel::class, 'service_iptv_equipment_id', 'id');
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
