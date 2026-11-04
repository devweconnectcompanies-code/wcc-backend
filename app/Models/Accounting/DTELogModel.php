<?php

namespace App\Models\Accounting;

use App\Models\Clients\ClientModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $dte_id
 * @property int|null $event_id
 * @property array<array-key, mixed> $json_response
 * @property \Illuminate\Support\Carbon $transaction_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $client_id
 * @property string|null $generation_code
 * @property array<array-key, mixed> $json_content
 * @property-read ClientModel|null $client
 * @property-read \App\Models\Accounting\DTEModel|null $dte
 * @property-read \App\Models\Accounting\DTEEventModel|null $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereDteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereGenerationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereJsonContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereJsonResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTELogModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DTELogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'accounting_dte_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dte_id',
        'event_id',
        'json_response',
        'transaction_date',
        'client_id',
        'generation_code',
        'json_content'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'dte_id' => 'integer',
        'event_id' => 'integer',
        'json_response' => 'array',
        'transaction_date' => 'datetime',
        'client_id' => 'integer',
        'generation_code' => 'string',
        'json_content' => 'array',
    ];

    public function dte(): BelongsTo
    {
        return $this->belongsTo(DTEModel::class, 'dte_id', 'id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(DTEEventModel::class, 'event_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }
}
