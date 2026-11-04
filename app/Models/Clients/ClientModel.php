<?php

namespace App\Models\Clients;

use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\ClientFinancialStatusModel;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\Options\DocumentTypeModel;
use App\Models\Billing\OtherInvoiceModel;
use App\Models\Billing\PrepaymentModel;
use App\Models\Configuration\BranchModel;
use App\Models\Configuration\Clients\ClientTypeModel;
use App\Models\Configuration\Clients\GenderModel;
use App\Models\Configuration\Clients\MaritalStatusModel;
use App\Models\Configuration\Geography\CountryModel;
use App\Models\Services\ServiceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use App\Observers\Clients\ClientObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ClientObserver::class])]
/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property int $gender_id
 * @property string|null $birthdate
 * @property int $marital_status_id
 * @property int $branch_id
 * @property int $client_type_id
 * @property string|null $profession
 * @property int $country_id
 * @property int $document_type_id
 * @property bool $legal_entity
 * @property bool $status_id
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ServiceModel> $active_services
 * @property-read int|null $active_services_count
 * @property-read \App\Models\Clients\AddressModel|null $address
 * @property-read BranchModel|null $branch
 * @property-read ClientTypeModel|null $client_type
 * @property-read \App\Models\Clients\FinancialInformationModel|null $corporate_info
 * @property-read CountryModel|null $country
 * @property-read DocumentTypeModel|null $document_type
 * @property-read \App\Models\Clients\DocumentModel|null $dui
 * @property-read \App\Models\Clients\EmailModel|null $email
 * @property-read ClientFinancialStatusModel|null $financial_status
 * @property-read GenderModel|null $gender
 * @property-read mixed $primary_document
 * @property-read array $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InvoiceModel> $invoices
 * @property-read int|null $invoices_count
 * @property-read \App\Models\Clients\PhoneModel|null $landline
 * @property-read MaritalStatusModel|null $marital_status
 * @property-read \App\Models\Clients\PhoneModel|null $mobile
 * @property-read \App\Models\Clients\DocumentModel|null $nit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OtherInvoiceModel> $other_invoices
 * @property-read int|null $other_invoices_count
 * @property-read \App\Models\Clients\DocumentModel|null $passport
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Clients\DocumentModel> $personal_documents
 * @property-read int|null $personal_documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PrepaymentModel> $prepayments
 * @property-read int|null $prepayments_count
 * @property-read \App\Models\Clients\DocumentModel|null $residence
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ServiceModel> $services
 * @property-read int|null $services_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereClientTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereDocumentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereLegalEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereMaritalStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientModel withoutTrashed()
 * @mixin \Eloquent
 */
class ClientModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'surname',
        'gender_id',
        'birthdate',
        'marital_status_id',
        'branch_id',
        'client_type_id',
        'profession',
        'country_id',
        'document_type_id',
        'legal_entity',
        'status_id',
        'comments'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'surname',
        'gender_id',
        'branch_id',
        'client_type_id',
        'status_id',
        'dui.number'
    ];
    protected array $orderable = [
        'id',
        'name',
        'surname',
        'gender_id',
        'branch_id',
        'client_type_id',
        'status_id',
    ];
    protected $appends = ['status'];

    public function getPrimaryDocumentAttribute()
    {
        $document = $this->dui ?? $this->nit ?? $this->passport ?? $this->residence;

        if (!$document) {
            return null;
        }

        return (object)[
            'type' => $document->document_type->name ?? 'Documento',
            'number' => $document->number,
        ];
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(GenderModel::class, 'gender_id', 'id');
    }

    public function marital_status(): BelongsTo
    {
        return $this->belongsTo(MaritalStatusModel::class, 'marital_status_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class, 'branch_id', 'id');
    }

    public function client_type(): BelongsTo
    {
        return $this->belongsTo(ClientTypeModel::class, 'client_type_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryModel::class, 'country_id', 'id');
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentTypeModel::class, 'document_type_id', 'id');
    }

    public function personal_documents(): HasMany
    {
        return $this->hasMany(DocumentModel::class, 'client_id', 'id');
    }

    public function dui(): HasOne
    {
        return $this->hasOne(DocumentModel::class, 'client_id', 'id')
            ->where('document_type_id', 3);
    }

    public function nit(): HasOne
    {
        return $this->hasOne(DocumentModel::class, 'client_id', 'id')
            ->where('document_type_id', 4);
    }

    public function passport(): HasOne
    {
        return $this->hasOne(DocumentModel::class, 'client_id', 'id')
            ->where('document_type_id', 2);
    }

    public function residence(): HasOne
    {
        return $this->hasOne(DocumentModel::class, 'client_id', 'id')
            ->where('document_type_id', 1);
    }

    public function mobile(): HasOne
    {
        return $this->hasOne(PhoneModel::class, 'client_id', 'id')
            ->where('phone_type_id', 2)
            ->latest();
    }

    public function landline(): HasOne
    {
        return $this->hasOne(PhoneModel::class, 'client_id', 'id')
            ->where('phone_type_id', 1)
            ->latest();
    }

    public function email(): HasOne
    {
        return $this->hasOne(EmailModel::class, 'client_id', 'id')
            ->where('status_id', 1)
            ->latest();
    }

    public function address(): HasOne
    {
        return $this->hasOne(AddressModel::class, 'client_id', 'id')
            ->where('status_id', 1)
            ->latest();
    }

    public function corporate_info(): HasOne
    {
        return $this->hasOne(FinancialInformationModel::class, 'client_id', 'id')
            ->where('status_id', CommonStatus::ACTIVE->value);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ServiceModel::class, 'client_id', 'id');
    }

    public function active_services(): HasMany
    {
        return $this->hasMany(ServiceModel::class, 'client_id', 'id')
            ->where('status_id', CommonStatus::ACTIVE->value);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceModel::class, 'client_id', 'id');
    }

    public function other_invoices(): HasMany
    {
        return $this->hasMany(OtherInvoiceModel::class, 'client_id', 'id');
    }

    public function financial_status(): HasOne
    {
        return $this->hasOne(ClientFinancialStatusModel::class, 'client_id', 'id');
    }

    public function prepayments(): HasMany
    {
        return $this->hasMany(PrepaymentModel::class, 'client_id', 'id');
    }

    public function active_prepayment_balance(): float
    {
        return $this->prepayments()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->sum('remaining_amount');
    }
}
