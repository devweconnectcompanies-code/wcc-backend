<?php

namespace App\Models\Accounting;

use App\Models\Accounting\Config\EventTypeModel;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $dte_id
 * @property string $generation_code
 * @property string $reception_stamp
 * @property \Illuminate\Support\Carbon $generation_datetime
 * @property int $user_id
 * @property array<array-key, mixed> $json_body
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $event_type_id
 * @property-read \App\Models\Accounting\DTEModel|null $dte
 * @property-read EventTypeModel|null $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereDteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereEventTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereGenerationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereGenerationDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereJsonBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereReceptionStamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEEventModel withoutTrashed()
 * @mixin \Eloquent
 */
class DTEEventModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'accounting_dte_events';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dte_id',
        'generation_code',
        'reception_stamp',
        'generation_datetime',
        'user_id',
        'json_body',
        'status_id',
        'event_type_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'dte_id',
        'generation_code',
        'generation_datetime',
        'user_id',
        'status_id',
        'event_type_id',
    ];
    protected array $orderable = [
        'id',
        'generation_datetime',
        'status_id',
    ];
    protected $casts = [
        'generation_datetime' => 'datetime',
        'json_body' => 'array',
        'status_id' => 'boolean',
    ];

    public function dte(): BelongsTo
    {
        return $this->belongsTo(DTEModel::class, 'dte_id', 'id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventTypeModel::class, 'event_type_id', 'id');
    }
}
