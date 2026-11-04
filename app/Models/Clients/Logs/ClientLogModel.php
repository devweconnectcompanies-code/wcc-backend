<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class ClientLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
