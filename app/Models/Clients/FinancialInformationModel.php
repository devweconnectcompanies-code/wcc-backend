<?php

namespace App\Models\Clients;

use App\Models\Billing\Options\ActivityModel;
use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use App\Observers\Clients\FinancialInformationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasStatusTrait;
use App\Traits\DataViewer;

#[ObservedBy(FinancialInformationObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property string $nrc
 * @property int $activity_id
 * @property bool $retained_iva
 * @property string $legal_representative
 * @property string $dui
 * @property string $nit
 * @property string $phone_number
 * @property string|null $invoice_alias
 * @property int $state_id
 * @property int $municipality_id
 * @property int $district_id
 * @property string $address
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read ActivityModel|null $activity
 * @property-read DistrictModel|null $district
 * @property-read array $status
 * @property-read MunicipalityModel|null $municipality
 * @property-read StateModel|null $state
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereDui($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereInvoiceAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereLegalRepresentative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereNrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereRetainedIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInformationModel withoutTrashed()
 * @mixin \Eloquent
 */
class FinancialInformationModel extends Model
{
    use DataViewer, HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'clients_financial_information';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'nrc',
        'activity_id',
        'retained_iva',
        'legal_representative',
        'dui',
        'nit',
        'phone_number',
        'invoice_alias',
        'state_id',
        'municipality_id',
        'district_id',
        'address',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'client_id',
        'nrc',
        'activity_id',
        'retained_iva',
        'legal_representative',
        'dui',
        'nit',
        'phone_number',
        'invoice_alias',
        'state_id',
        'municipality_id',
        'district_id',
        'address',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'client_id',
        'nrc',
        'activity_id',
        'retained_iva',
        'legal_representative',
        'dui',
        'nit',
        'phone_number',
        'invoice_alias',
        'state_id',
        'municipality_id',
        'district_id',
        'address',
        'status_id',
    ];
    protected $appends = ['status'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(ActivityModel::class, 'activity_id', 'id');
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
}
