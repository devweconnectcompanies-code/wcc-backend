<?php

namespace App\Models\Supports;

use App\Models\Clients\ClientModel;
use App\Models\Clients\ContractModel;
use App\Models\Configuration\BranchModel;
use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use App\Models\Management\TechnicianModel;
use App\Models\Services\ServiceModel;
use App\Models\User;
use App\Observers\Supports\SupportObserver;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([SupportObserver::class])]
/**
 * @property int $id
 * @property int $type_id
 * @property string $ticket_number
 * @property int $client_id
 * @property int|null $contract_id
 * @property int|null $service_id
 * @property int $branch_id
 * @property string $creation_date
 * @property string $due_date
 * @property string $description
 * @property int|null $technician_id
 * @property int $state_id
 * @property int $municipality_id
 * @property int $district_id
 * @property string $address
 * @property string|null $closed_at
 * @property string|null $solution
 * @property string|null $comments
 * @property int $user_id
 * @property int $status_id
 * @property bool $breached_sla
 * @property int|null $resolution_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read BranchModel|null $branch
 * @property-read ClientModel|null $client
 * @property-read ContractModel|null $contract
 * @property-read \App\Models\Supports\SupportDetailModel|null $details
 * @property-read DistrictModel|null $district
 * @property-read MunicipalityModel|null $municipality
 * @property-read ServiceModel|null $service
 * @property-read StateModel|null $state
 * @property-read \App\Models\Supports\StatusModel|null $status
 * @property-read \App\Models\Supports\SupportRatingModel|null $survey
 * @property-read TechnicianModel|null $technician
 * @property-read \App\Models\Supports\TypeModel|null $type
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereBreachedSla($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereCreationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereResolutionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereSolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportModel withoutTrashed()
 * @mixin \Eloquent
 */
class SupportModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'supports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type_id',
        'ticket_number',
        'client_id',
        'service_id',
        'contract_id',
        'branch_id',
        'creation_date',
        'due_date',
        'description',
        'technician_id',
        'state_id',
        'municipality_id',
        'district_id',
        'address',
        'closed_at',
        'solution',
        'comments',
        'user_id',
        'status_id',
        'breached_sla',
        'resolution_time',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'type_id',
        'ticket_number',
        'client_id',
        'service_id',
        'branch_id',
        'creation_date',
        'due_date',
        'technician_id',
        'state_id',
        'municipality_id',
        'district_id',
        'closed_at',
        'user_id',
        'status_id',
        'breached_sla',
        'client.name',
        'client.surname',
        'client.dui.number',
    ];
    protected array $orderable = [
        'id',
        'type_id',
        'ticket_number',
        'client_id',
        'service_id',
        'branch_id',
        'creation_date',
        'due_date',
        'technician_id',
        'state_id',
        'municipality_id',
        'district_id',
        'closed_at',
        'user_id',
        'status_id',
        'breached_sla',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeModel::class, 'type_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class, 'branch_id', 'id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(TechnicianModel::class, 'technician_id', 'id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(StateModel::class, 'state_id', 'id');
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(MunicipalityModel::class, 'municipality_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(DistrictModel::class, 'district_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    public function details(): HasOne
    {
        return $this->hasOne(SupportDetailModel::class, 'support_id', 'id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(ContractModel::class, 'contract_id', 'id');
    }

    public function survey(): HasOne
    {
        return $this->hasOne(SupportRatingModel::class, 'support_id', 'id');
    }
}
