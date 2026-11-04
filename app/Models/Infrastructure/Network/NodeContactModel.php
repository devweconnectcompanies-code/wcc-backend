<?php

namespace App\Models\Infrastructure\Network;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property int $node_id
 * @property string $name
 * @property string $phone_number
 * @property string $initial_contract_date
 * @property string $final_contract_date
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read int|null $diff_days
 * @property-read array $status
 * @property-read \App\Models\Infrastructure\Network\NodeModel|null $node
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereFinalContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereInitialContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeContactModel withoutTrashed()
 * @mixin \Eloquent
 */
class NodeContactModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_nodes_contacts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'node_id',
        'name',
        'phone_number',
        'initial_contract_date',
        'final_contract_date',
        'status_id'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'node_id', 'name', 'phone_number', 'status_id'];
    protected array $orderable = ['id', 'node_id', 'name', 'phone_number', 'status_id'];
    protected $appends = ['status', 'diff_days'];

    public function node(): BelongsTo
    {
        return $this->belongsTo(NodeModel::class, 'node_id', 'id');
    }

    public function getDiffDaysAttribute(): ?int
    {
        if (!$this->final_contract_date)
            return null;
        try {

            $today = Carbon::today();
            $end = Carbon::parse($this->final_contract_date);
            return $today->diffInDays($end, false);
        } catch (\Exception $e) {
            return null;
        }
    }
}
