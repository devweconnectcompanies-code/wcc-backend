<?php

namespace App\Models\Clients;

use App\Models\Configuration\Clients\ContractStateModel;
use App\Models\Supports\SupportModel;
use App\Observers\Clients\ContractObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasStatusTrait;

#[ObservedBy(ContractObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property string $contract_date
 * @property string $contract_end_date
 * @property numeric $installation_price
 * @property numeric $contract_amount
 * @property int $contract_status_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Clients\ClientModel|null $client
 * @property-read ContractStateModel|null $contract_status
 * @property-read int|null $diff_days
 * @property-read array $status
 * @property-read SupportModel|null $support
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereContractAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereContractEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereContractStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereInstallationPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractModel withoutTrashed()
 * @mixin \Eloquent
 */
class ContractModel extends Model
{
    use HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'clients_contracts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'contract_date',
        'contract_end_date',
        'installation_price',
        'contract_amount',
        'contract_status_id',
        'status_id'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['status', 'diff_days'];

    public function contract_status(): HasOne
    {
        return $this->hasOne(ContractStateModel::class, 'id', 'contract_status_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function support(): HasOne
    {
        return $this->hasOne(SupportModel::class, 'contract_id', 'id');
    }

    public function getDiffDaysAttribute(): ?int
    {
        if (!$this->contract_end_date) return null;
        try {
            $today = Carbon::today();
            $end = Carbon::parse($this->contract_end_date);
            return $today->diffInDays($end, false);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
