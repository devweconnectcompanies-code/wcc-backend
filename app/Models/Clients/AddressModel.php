<?php

namespace App\Models\Clients;

use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use App\Observers\Clients\AddressObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(AddressObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property string $neighborhood
 * @property string $address
 * @property int $state_id
 * @property int $municipality_id
 * @property int $district_id
 * @property int $country_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Clients\ClientModel|null $client
 * @property-read DistrictModel|null $district
 * @property-read array $status
 * @property-read MunicipalityModel|null $municipality
 * @property-read StateModel|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereNeighborhood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressModel withoutTrashed()
 * @mixin \Eloquent
 */
class AddressModel extends Model
{
    use DataViewer, HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'clients_addresses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'neighborhood',
        'address',
        'state_id',
        'municipality_id',
        'district_id',
        'country_id'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'client_id',
        'neighborhood',
        'state_id',
        'municipality_id',
        'district_id',
        'country_id'
    ];
    protected array $orderable = [
        'id',
        'client_id',
        'neighborhood',
        'state_id',
        'municipality_id',
        'district_id',
        'country_id'
    ];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function state(): HasOne
    {
        return $this->hasOne(StateModel::class, 'id', 'state_id');
    }

    public function municipality(): HasOne
    {
        return $this->hasOne(MunicipalityModel::class, 'id', 'municipality_id');
    }

    public function district(): HasOne
    {
        return $this->hasOne(DistrictModel::class, 'id', 'district_id');
    }
}
