<?php

namespace App\Models\Configuration\Clients;

use App\Models\Clients\ClientModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ClientModel> $clients
 * @property-read int|null $clients_count
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientTypeModel withoutTrashed()
 * @mixin \Eloquent
 */
class ClientTypeModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_client_types';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];

    public function clients(): HasMany
    {
        return $this->hasMany(ClientModel::class, 'client_type_id', 'id');
    }
}
