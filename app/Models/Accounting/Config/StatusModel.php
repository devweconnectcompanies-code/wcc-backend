<?php

namespace App\Models\Accounting\Config;

use App\Models\Accounting\DTEModel;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $badge_color
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DTEModel> $dte
 * @property-read int|null $dte_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel withoutTrashed()
 * @mixin \Eloquent
 */
class StatusModel extends Model
{
    use DataViewer, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'accounting_dte_statuses';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'badge_color', 'status'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name'];
    protected array $orderable = ['id', 'name'];
    protected $casts = ['name' => 'string', 'badge_color' => 'string', 'status' => 'boolean'];

    public function dte(): HasMany
    {
        return $this->hasMany(DTEModel::class, 'status_id', 'id');
    }
}
