<?php

namespace App\Models\Accounting;

use App\Enums\v1\Accounting\DTE\EventTypes;
use App\Enums\v1\Accounting\InvoiceCategories;
use App\Models\Accounting\Config\StatusModel;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\Options\DocumentTypeModel;
use App\Models\Billing\OtherInvoiceModel;
use App\Models\Billing\PaymentModel;
use App\Models\Clients\ClientModel;
use App\Models\User;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $client_id
 * @property int $document_type_id
 * @property string $control_number
 * @property string $generation_code
 * @property string $reception_stamp
 * @property \Illuminate\Support\Carbon $generation_datetime
 * @property numeric $total_amount
 * @property int|null $payment_id
 * @property InvoiceCategories $invoice_category
 * @property int|null $other_invoice_id
 * @property int $user_id
 * @property array<array-key, mixed> $json_body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $status_id
 * @property-read ClientModel|null $client
 * @property-read DocumentTypeModel|null $dte_type
 * @property-read \App\Models\Billing\InvoiceModel|\App\Models\Billing\OtherInvoiceModel|null $related_invoice
 * @property-read \App\Models\Accounting\DTEEventModel|null $invalidation
 * @property-read \App\Models\Accounting\DTEInvoiceModel|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InvoiceModel> $invoices
 * @property-read int|null $invoices_count
 * @property-read OtherInvoiceModel|null $other_invoice
 * @property-read PaymentModel|null $payment
 * @property-read \App\Models\Accounting\DTEEventModel|null $refund
 * @property-read StatusModel|null $status
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel invalidated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereControlNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereDocumentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereGenerationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereGenerationDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereInvoiceCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereJsonBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereOtherInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereReceptionStamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEModel withoutTrashed()
 * @mixin \Eloquent
 */
class DTEModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'accounting_dte';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'document_type_id',
        'control_number',
        'generation_code',
        'reception_stamp',
        'generation_datetime',
        'total_amount',
        'payment_id',
        'invoice_category',
        'invoice_id',
        'other_invoice_id',
        'user_id',
        'status_id',
        'json_body'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'client_id',
        'document_type_id',
        'control_number',
        'generation_code',
        'reception_stamp',
        'generation_datetime',
        'invoice_category',
        'user_id',
        'status_id',
        'client.name',
        'client.surname',
    ];
    protected array $orderable = [
        'id',
        'client_id',
        'document_type_id',
        'control_number',
        'generation_code',
        'reception_stamp',
        'generation_datetime',
        'invoice_category',
        'user_id',
        'status_id',
    ];
    protected $casts = [
        'generation_datetime' => 'datetime',
        'total_amount' => 'decimal:2',
        'json_body' => 'array',
        'invoice_category' => InvoiceCategories::class,
        'status_id' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function dte_type(): BelongsTo
    {
        return $this->belongsTo(DocumentTypeModel::class, 'document_type_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentModel::class, 'payment_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(
            InvoiceModel::class,
            'accounting_dte_invoices',
            'dte_id',
            'invoice_id'
        )
            ->using(DTEInvoiceModel::class)
            ->withTimestamps()
            ->withTrashed();
    }

    public function other_invoice(): BelongsTo
    {
        return $this->belongsTo(OtherInvoiceModel::class, 'other_invoice_id', 'id');
    }

    public function getRelatedInvoiceAttribute(): InvoiceModel|OtherInvoiceModel|null
    {
        return match ($this->invoice_category) {
            InvoiceCategories::INVOICE => $this->invoices,
            InvoiceCategories::OTHER_INVOICE => $this->other_invoice,
            default => throw new \LogicException("Categoría de factura inválida."),
        };

        /***
         * Uso
         * $dte->related_invoice
         */
    }

    public function invalidation(): HasOne
    {
        return $this->hasOne(DTEEventModel::class, 'dte_id', 'id')
            ->where('event_type_id', EventTypes::INVALIDACION->value);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(DTEEventModel::class, 'dte_id', 'id')
            ->where('event_type_id', EventTypes::RETORNO->value);
    }

    public function scopeInvalidated($query)
    {
        return $query->whereHas('invalidation');
    }

    /***
     * // Solo invalidados
     * DTEModel::invalidated()->get();
     *
     * // Con datos del evento cargados
     * DTEModel::invalidated()
     * ->with('invalidation')
     * ->get();
     *
     * // Combinado con otros filtros
     * DTEModel::invalidated()
     * ->with(['invalidation', 'client', 'dte_type'])
     * ->where('document_type_id', 1)
     * ->paginate(20);
     */
}
