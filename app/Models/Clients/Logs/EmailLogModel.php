<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Clients\EmailModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $email_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read EmailModel|null $email
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class EmailLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_emails_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['email_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function email(): BelongsTo
    {
        return $this->belongsTo(EmailModel::class, 'email_id', 'id');
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
