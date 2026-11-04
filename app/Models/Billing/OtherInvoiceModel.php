<?php

namespace App\Models\Billing;

use App\Models\Billing\Options\DocumentTypeModel;
use App\Models\Billing\Options\PaymentMethodModel;
use App\Models\Clients\ClientModel;
use App\Models\User;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $document_type_id
 * @property int $client_id
 * @property int $payment_condition
 * @property int $payment_method_id
 * @property numeric $subtotal
 * @property numeric $neto
 * @property numeric $iva
 * @property numeric $iva_retenido
 * @property numeric $discount_amount
 * @property numeric $total_amount
 * @property int $issue_date
 * @property int $created_by
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read ClientModel|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\OtherInvoiceDetailModel> $details
 * @property-read int|null $details_count
 * @property-read DocumentTypeModel|null $dte_type
 * @property-read array $status
 * @property-read PaymentMethodModel|null $payment_method
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereDocumentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereIvaRetenido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereNeto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel wherePaymentCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceModel withoutTrashed()
 * @mixin \Eloquent
 */
class OtherInvoiceModel extends Model
{
    use DataViewer, HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_other_invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'document_type_id',
        'client_id',
        'payment_condition',
        'payment_method_id',
        'subtotal',
        'neto',
        'iva',
        'iva_retenido',
        'discount_amount',
        'total_amount',
        'issue_date',
        'created_by',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'document_type_id',
        'client_id',
        'payment_condition',
        'payment_method_id',
        'issue_date',
        'created_by',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'document_type_id',
        'client_id',
        'payment_condition',
        'payment_method_id',
        'issue_date',
        'created_by',
        'status_id',

    ];
    protected $casts = [
        'subtotal' => 'decimal:8',
        'neto' => 'decimal:8',
        'iva' => 'decimal:8',
        'iva_retenido' => 'decimal:8',
        'discount_amount' => 'decimal:8',
        'total_amount' => 'decimal:8',
        'issue_date' => 'timestamp',
        'created_by' => 'integer',
    ];
    protected $appends = ['status'];

    public function dte_type(): BelongsTo
    {
        return $this->belongsTo(DocumentTypeModel::class, 'document_type_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OtherInvoiceDetailModel::class, 'other_invoice_id', 'id');
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodModel::class, 'payment_method_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
