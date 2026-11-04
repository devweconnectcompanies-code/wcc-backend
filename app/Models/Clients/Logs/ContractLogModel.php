<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Clients\ContractModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $contract_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read ContractModel|null $contract
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class ContractLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_contracts_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['contract_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(ContractModel::class, 'contract_id', 'id');
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
