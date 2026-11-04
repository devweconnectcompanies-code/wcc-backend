<?php

namespace App\Models\Services;

use App\Models\Infrastructure\Equipment\InventoryModel;
use App\Observers\Services\ServiceIPTVEquipmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

#[ObservedBy(ServiceIptvEquipmentObserver::class)]
/**
 * @property int $id
 * @property int $equipment_id
 * @property int $service_id
 * @property string $email
 * @property string $email_password
 * @property string $iptv_password
 * @property string|null $comments
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $email_correlative
 * @property-read InventoryModel|null $equipment
 * @property-read array $status
 * @property-read \App\Models\Services\ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereEmailCorrelative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereEmailPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereIptvPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceIptvEquipmentModel withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceIptvEquipmentModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'services_iptv_equipment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'equipment_id',
        'service_id',
        'email',
        'email_password',
        'iptv_password',
        'comments',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'equipment_id',
        'service_id',
        'status_id',
        'email',
    ];
    protected array $orderable = [
        'id',
        'equipment_id',
        'service_id',
        'status_id',
        'email',
    ];
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
