<?php

namespace App\Models\Infrastructure\Network;

use App\Models\Configuration\Infrastructure\EquipmentStatusModel;
use App\Models\Infrastructure\Equipment\BrandModel;
use App\Models\Infrastructure\Equipment\ModelModel;
use App\Models\Infrastructure\Equipment\TypeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;

/**
 * @property int $id
 * @property string $name
 * @property int $type_id
 * @property int $brand_id
 * @property int $model_id
 * @property string $mac_address
 * @property string $ip_address
 * @property string $username
 * @property string $secret
 * @property int $node_id
 * @property string|null $comments
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read BrandModel|null $brand
 * @property-read ModelModel|null $model
 * @property-read \App\Models\Infrastructure\Network\NodeModel|null $node
 * @property-read EquipmentStatusModel|null $status
 * @property-read TypeModel|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereMacAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipmentModel withoutTrashed()
 * @mixin \Eloquent
 */
class EquipmentModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_equipment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type_id',
        'brand_id',
        'model_id',
        'mac_address',
        'ip_address',
        'username',
        'secret',
        'node_id',
        'comments',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'type_id',
        'brand_id',
        'model_id',
        'node_id',
        'mac_address',
        'ip_address',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'name',
        'type_id',
        'brand_id',
        'model_id',
        'node_id',
        'mac_address',
        'ip_address',
        'status_id',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(EquipmentStatusModel::class, 'status_id', 'id')
            ->where('status_id', 1);
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(NodeModel::class, 'node_id', 'id')
            ->where('status_id', 1);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeModel::class, 'type_id', 'id')
            ->where('status_id', 1);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class, 'brand_id', 'id')
            ->where('status_id', 1);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(ModelModel::class, 'model_id', 'id')
            ->where('status_id', 1);
    }
}
