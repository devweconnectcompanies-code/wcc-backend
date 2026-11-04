<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Clients\PhoneModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $phone_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read PhoneModel|null $phone
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel wherePhoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class PhoneLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_phones_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['phone_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function phone(): BelongsTo
    {
        return $this->belongsTo(PhoneModel::class, 'phone_id', 'id');
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
