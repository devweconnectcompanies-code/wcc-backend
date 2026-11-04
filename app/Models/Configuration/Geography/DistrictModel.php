<?php

namespace App\Models\Configuration\Geography;

use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $municipality_id
 * @property int $state_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $code
 * @property-read \App\Models\Configuration\Geography\MunicipalityModel|null $municipality
 * @property-read \App\Models\Configuration\Geography\StateModel|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DistrictModel withoutTrashed()
 * @mixin \Eloquent
 */
class DistrictModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'config_districts';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'municipality_id', 'state_id', 'status_id', 'code'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'municipality_id', 'state_id', 'status_id', 'code'];
    protected array $orderable = ['id', 'name', 'municipality_id', 'state_id', 'status_id'];

    public function municipality(): belongsTo
    {
        return $this->belongsTo(MunicipalityModel::class, 'municipality_id', 'id');
    }

    public function state(): belongsTo
    {
        return $this->belongsTo(StateModel::class, 'state_id', 'id');
    }
}
