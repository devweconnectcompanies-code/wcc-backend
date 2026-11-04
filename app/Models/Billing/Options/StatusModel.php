<?php

namespace App\Models\Billing\Options;

use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string|null $badge_color
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusModel withoutTrashed()
 * @mixin \Eloquent
 */
class StatusModel extends Model
{
    use SoftDeletes, HasStatusTrait, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'billing_statuses';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'badge_color', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
