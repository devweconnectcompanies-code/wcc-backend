<?php

namespace App\Models\Services\Logs;

use App\Models\Services\ServiceInternetModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $internet_service_id
 * @property int $service_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ServiceInternetModel|null $internet_service
 * @property-read ServiceModel|null $service
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereInternetServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetServiceLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class InternetServiceLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'internet_services_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'internet_service_id',
        'service_id',
        'user_id',
        'action',
        'before',
        'after',
    ];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function internet_service(): BelongsTo
    {
        return $this->belongsTo(ServiceInternetModel::class, 'internet_service_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
