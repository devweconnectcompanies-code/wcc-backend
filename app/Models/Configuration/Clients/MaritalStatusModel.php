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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaritalStatusModel withoutTrashed()
 * @mixin \Eloquent
 */
class MaritalStatusModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_marital_status';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
