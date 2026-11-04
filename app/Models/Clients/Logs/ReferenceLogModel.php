<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Clients\ReferenceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $reference_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read ReferenceModel|null $reference
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class ReferenceLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_references_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['reference_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function reference(): BelongsTo
    {
        return $this->belongsTo(ReferenceModel::class, 'reference_id', 'id');
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
