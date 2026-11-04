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
 * @property string|null $badge_color
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractStateModel withoutTrashed()
 * @mixin \Eloquent
 */
class ContractStateModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_contracts_status';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'badge_color', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
