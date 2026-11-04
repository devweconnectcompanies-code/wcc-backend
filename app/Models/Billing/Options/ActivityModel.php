<?php

namespace App\Models\Billing\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityModel withoutTrashed()
 * @mixin \Eloquent
 */
class ActivityModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'billing_activity_categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'code', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'code', 'status_id'];
    protected array $orderable = ['id', 'name', 'code', 'status_id'];
    protected $appends = ['status'];
}
