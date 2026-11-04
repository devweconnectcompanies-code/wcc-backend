<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Clients\FinancialInformationModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $finance_information_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read FinancialInformationModel|null $financial_information
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereFinanceInformationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class FinancialInformationLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_financial_information_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['finance_information_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function financial_information(): BelongsTo
    {
        return $this->belongsTo(FinancialInformationModel::class, 'finance_information_id', 'id');
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
