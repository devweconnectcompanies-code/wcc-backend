<?php

namespace App\Models\Services\Logs;

use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Models\Services\ServiceEquipmentModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $service_equipment_id
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
 * @property-read ServiceEquipmentModel|null $service_equipment
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereServiceEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceEquipmentLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class ServiceEquipmentLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'services_equipment_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_equipment_id',
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

    public function service_equipment(): BelongsTo
    {
        return $this->belongsTo(ServiceEquipmentModel::class, 'service_equipment_id', 'id');
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
