<?php

namespace App\Models\Services;

use App\Models\Billing\PeriodModel;
use App\Models\Clients\ClientModel;
use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use App\Models\Infrastructure\Network\EquipmentModel;
use App\Models\Infrastructure\Network\NodeModel;
use App\Models\Management\TechnicianModel;
use App\Models\Supports\SupportModel;
use App\Observers\Services\ServiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([ServiceObserver::class])]
/**
 * @property int $id
 * @property int $client_id
 * @property string|null $code
 * @property string|null $name
 * @property int $node_id
 * @property int $equipment_id
 * @property string $installation_date
 * @property int $technician_id
 * @property numeric $latitude
 * @property numeric $longitude
 * @property int $state_id
 * @property int $municipality_id
 * @property int $district_id
 * @property string $address
 * @property bool $separate_billing
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $comments
 * @property-read ClientModel|null $client
 * @property-read DistrictModel|null $district
 * @property-read EquipmentModel|null $equipment
 * @property-read array $status
 * @property-read \App\Models\Services\ServiceInternetModel|null $internet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Services\ServiceEquipmentModel> $internet_devices
 * @property-read int|null $internet_devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Services\ServiceIptvEquipmentModel> $iptv_devices
 * @property-read int|null $iptv_devices_count
 * @property-read MunicipalityModel|null $municipality
 * @property-read NodeModel|null $node
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Services\ServiceSoldDeviceModel> $sold_devices
 * @property-read int|null $sold_devices_count
 * @property-read StateModel|null $state
 * @property-read TechnicianModel|null $technician
 * @property-read \App\Models\Services\ServiceUninstallationModel|null $uninstallation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel activeOrUninstalledInPeriod(\App\Models\Billing\PeriodModel $period)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereInstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereSeparateBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceModel withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'code',
        'name',
        'node_id',
        'equipment_id',
        'installation_date',
        'technician_id',
        'latitude',
        'longitude',
        'state_id',
        'municipality_id',
        'district_id',
        'address',
        'separate_billing',
        'status_id',
        'comments',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'code',
        'node_id',
        'equipment_id',
        'technician_id',
        'state_id',
        'municipality_id',
        'district_id',
        'status_id',
        'client.name',
        'client.dui.number'
    ];
    protected array $orderable = [
        'id',
        'code',
        'node_id',
        'equipment_id',
        'technician_id',
        'state_id',
        'municipality_id',
        'district_id',
        'status_id',
    ];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(NodeModel::class, 'node_id', 'id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(EquipmentModel::class, 'equipment_id', 'id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(TechnicianModel::class, 'technician_id', 'id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(StateModel::class, 'state_id', 'id');
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(MunicipalityModel::class, 'municipality_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(DistrictModel::class, 'district_id', 'id');
    }

    public function internet(): HasOne
    {
        return $this->hasOne(ServiceInternetModel::class, 'service_id', 'id');
    }

    public function internet_devices(): HasMany
    {
        return $this->hasMany(ServiceEquipmentModel::class, 'service_id', 'id');
    }

    public function iptv_devices(): HasMany
    {
        return $this->hasMany(ServiceIptvEquipmentModel::class, 'service_id', 'id');
    }

    public function sold_devices(): HasMany
    {
        return $this->hasMany(ServiceSoldDeviceModel::class, 'service_id', 'id');
    }

    public function uninstallation(): HasOne
    {
        return $this->hasOne(ServiceUninstallationModel::class, 'service_id', 'id');
    }

    public function supports(): HasMany
    {
        return $this->hasMany(SupportModel::class, 'service_id', 'id');
    }

    public function last_support(): HasOne
    {
        return $this->hasOne(SupportModel::class, 'service_id', 'id')
            ->latestOfMany('creation_date');
    }

    public function scopeActiveOrUninstalledInPeriod($query, PeriodModel $period)
    {
        return $query->where(function ($q) use ($period) {
            // Servicios activos (pueden o no tener desinstalación futura)
            $q->where('status_id', true)
                // O servicios inactivos que fueron desinstalados durante este período
                ->orWhere(function ($subQ) use ($period) {
                    $subQ->where('status_id', false)
                        ->whereHas('uninstallation', function ($uninstQ) use ($period) {
                            $uninstQ->whereBetween('uninstallation_date', [
                                $period->period_start,
                                $period->period_end
                            ]);
                        });
                });
        });
    }
}
