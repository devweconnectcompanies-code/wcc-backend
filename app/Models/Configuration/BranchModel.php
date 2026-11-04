<?php

namespace App\Models\Configuration;

use App\Models\Configuration\Geography\CountryModel;
use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string|null $landline
 * @property string|null $mobile
 * @property string $address
 * @property int $state_id
 * @property int $municipality_id
 * @property int $district_id
 * @property int $country_id
 * @property string $badge_color
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read CountryModel|null $country
 * @property-read DistrictModel|null $district
 * @property-read array $status
 * @property-read MunicipalityModel|null $municipality
 * @property-read StateModel|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereLandline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchModel withoutTrashed()
 * @mixin \Eloquent
 */
class BranchModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_branches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'code',
        'landline',
        'mobile',
        'address',
        'state_id',
        'municipality_id',
        'district_id',
        'country_id',
        'badge_color',
        'status_id'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'code',
        'landline',
        'mobile',
        'state_id',
        'municipality_id',
        'status_id'
    ];
    protected array $orderable = ['id', 'name', 'code', 'status_id'];
    protected $appends = ['status'];

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

    public function country(): HasOne
    {
        return $this->hasOne(CountryModel::class, 'id', 'country_id');
    }
}
