<?php

namespace App\Models\Services\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $service_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read ServiceModel|null $service
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class ServiceLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'services_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_id',
        'client_id',
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
