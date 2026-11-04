<?php

namespace App\Models\Infrastructure\Network;

use App\Enums\v1\General\CommonStatus;
use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property int $server_id
 * @property numeric $latitude
 * @property numeric $longitude
 * @property int $state_id
 * @property int $municipality_id
 * @property int $district_id
 * @property string $address
 * @property string $nc
 * @property string $nc_owner
 * @property string|null $comments
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $prefix
 * @property-read \App\Models\Infrastructure\Network\AuthServerModel|null $auth_server
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Infrastructure\Network\NodeContactModel> $contacts
 * @property-read int|null $contacts_count
 * @property-read DistrictModel|null $district
 * @property-read array $status
 * @property-read MunicipalityModel|null $municipality
 * @property-read StateModel|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereNc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereNcOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeModel withoutTrashed()
 * @mixin \Eloquent
 */
class NodeModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_nodes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'prefix',
        'server_id',
        'latitude',
        'longitude',
        'state_id',
        'municipality_id',
        'district_id',
        'address',
        'nc',
        'nc_owner',
        'comments',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'prefix',
        'nc',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'name',
        'status_id',
    ];
    protected $appends = ['status'];

    public function auth_server(): BelongsTo
    {
        return $this->belongsTo(AuthServerModel::class, 'server_id', 'id')
            ->where('status_id', CommonStatus::ACTIVE->value);
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

    public function contacts(): HasMany
    {
        return $this->hasMany(NodeContactModel::class, 'node_id', 'id')
            ->where('status_id', 1);
    }
}
