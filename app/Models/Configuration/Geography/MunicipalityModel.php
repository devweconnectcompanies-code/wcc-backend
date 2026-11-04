<?php

namespace App\Models\Configuration\Geography;

use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $state_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Configuration\Geography\StateModel|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MunicipalityModel withoutTrashed()
 * @mixin \Eloquent
 */
class MunicipalityModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'config_municipalities';
    protected $fillable = ['name', 'code', 'state_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'code', 'state_id', 'status_id'];
    protected array $orderable = ['id', 'name', 'code', 'state_id', 'status_id'];

    public function state(): BelongsTo
    {
        return $this->belongsTo(StateModel::class, 'state_id', 'id');
    }
}
