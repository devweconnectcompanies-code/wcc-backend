<?php

namespace App\Models\Accounting\Config;

use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $badge_color
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DTEModel> $invalidated_dtes
 * @property-read int|null $invalidated_dtes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTypeModel withoutTrashed()
 * @mixin \Eloquent
 */
class EventTypeModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'accounting_dte_event_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'code',
        'badge_color',
        'status',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'code',
        'status',
    ];
    protected array $orderable = [
        'id',
        'name',
        'code',
        'status',
    ];
    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'badge_color' => 'string',
        'status' => 'boolean',
    ];

    public function invalidated_dtes(): HasManyThrough
    {
        return $this->hasManyThrough(
            DTEModel::class,
            DTEEventModel::class,
            'event_type_id',
            'id',
            'id',
            'dte_id'
        );
    }
}
