<?php

namespace App\Models\Configuration\Clients;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KinshipModel withoutTrashed()
 * @mixin \Eloquent
 */
class KinshipModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_kinships';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
