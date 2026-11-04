<?php

namespace App\Models\Infrastructure\Equipment;

use App\Models\Configuration\BranchModel;
use App\Models\Configuration\Infrastructure\EquipmentStatusModel;
use App\Models\Services\ServiceEquipmentModel;
use App\Models\Services\ServiceIptvEquipmentModel;
use App\Models\Services\ServiceSoldDeviceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasCompanyTrait;

/**
 * @property int $id
 * @property int $brand_id
 * @property int $type_id
 * @property int $model_id
 * @property int $branch_id
 * @property string $mac_address
 * @property string $serial_number
 * @property string $registration_date
 * @property int $status_id
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $company_id
 * @property-read BranchModel|null $branch
 * @property-read \App\Models\Infrastructure\Equipment\BrandModel|null $brand
 * @property-read array $company
 * @property-read \App\Models\Infrastructure\Equipment\InventoryLogModel|null $last_technician
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Infrastructure\Equipment\InventoryLogModel> $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\Infrastructure\Equipment\ModelModel|null $model
 * @property-read ServiceEquipmentModel|null $on_internet_service
 * @property-read ServiceIptvEquipmentModel|null $on_iptv_service
 * @property-read ServiceSoldDeviceModel|null $on_sold_devices
 * @property-read EquipmentStatusModel|null $status
 * @property-read \App\Models\Infrastructure\Equipment\TypeModel|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereMacAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereRegistrationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryModel withoutTrashed()
 * @mixin \Eloquent
 */
class InventoryModel extends Model
{
    use SoftDeletes, DataViewer, HasCompanyTrait;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_residential_equipment_inventory';
    protected $primaryKey = 'id';
    protected $fillable = [
        'brand_id',
        'type_id',
        'model_id',
        'branch_id',
        'mac_address',
        'serial_number',
        'registration_date',
        'status_id',
        'comments',
        'company_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'type_id',
        'model_id',
        'branch_id',
        'mac_address',
        'serial_number',
        'status_id',
        'company_id',
    ];
    protected array $orderable = [
        'id',
        'type_id',
        'model_id',
        'branch_id',
        'mac_address',
        'serial_number',
        'status_id',
        'company_id',
    ];
    protected $appends = ['company'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class, 'brand_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeModel::class, 'type_id', 'id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(ModelModel::class, 'model_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class, 'branch_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(EquipmentStatusModel::class, 'status_id', 'id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InventoryLogModel::class, 'equipment_id', 'id');
    }

    public function last_technician(): HasOne
    {
        return $this->hasOne(InventoryLogModel::class, 'equipment_id', 'id')
            ->latestOfMany('execution_date');
    }

    public function on_internet_service(): HasOne
    {
        return $this->hasOne(ServiceEquipmentModel::class, 'equipment_id', 'id');
    }

    public function on_iptv_service(): HasOne
    {
        return $this->hasOne(ServiceIptvEquipmentModel::class, 'equipment_id', 'id');
    }

    public function on_sold_devices(): HasOne
    {
        return $this->hasOne(ServiceSoldDeviceModel::class, 'equipment_id', 'id');
    }
}
