<?php

namespace App\Models\Configuration\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenderModel withoutTrashed()
 * @mixin \Eloquent
 */
class GenderModel extends Model
{
    use SoftDeletes, HasStatusTrait, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'config_genders';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];

}
