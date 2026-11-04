<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\AddressModel;
use App\Models\Clients\ClientModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $address_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read AddressModel|null $address
 * @property-read ClientModel|null $client
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class AddressLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_addresses_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['address_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(AddressModel::class, 'address_id', 'id');
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
