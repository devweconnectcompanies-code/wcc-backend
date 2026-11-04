<?php

namespace App\Models\Configuration\Geography;

use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $iso_code
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StateModel withoutTrashed()
 * @mixin \Eloquent
 */
class StateModel extends Model
{
    use DataViewer, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'config_states';
    protected $fillable = ['name', 'code', 'iso_code', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'code', 'iso_code', 'status_id'];
    protected array $orderable = ['id', 'name', 'code', 'status_id'];
}
