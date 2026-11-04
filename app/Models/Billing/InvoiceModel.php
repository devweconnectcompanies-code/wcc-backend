<?php

namespace App\Models\Billing;

use App\Models\Accounting\DTEInvoiceModel;
use App\Models\Accounting\DTEModel;
use App\Models\Billing\Options\StatusModel;
use App\Models\Clients\ClientModel;
use App\Observers\Billing\InvoiceObserver;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([InvoiceObserver::class])]
/**
 * @property int $id
 * @property int $client_id
 * @property int $billing_period_id
 * @property int $invoice_type
 * @property numeric $subtotal
 * @property numeric $iva
 * @property numeric $iva_retenido
 * @property numeric $total_amount
 * @property numeric $paid_amount
 * @property numeric $balance_due
 * @property int $billing_status_id
 * @property bool $status_id
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read ClientModel|null $client
 * @property-read \App\Models\Billing\PaymentInvoiceModel|DTEInvoiceModel|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DTEModel> $dtes
 * @property-read int|null $dtes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\InvoiceExtensionModel> $extensions
 * @property-read int|null $extensions_count
 * @property-read StatusModel|null $financial_status
 * @property-read array $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\InvoiceDetailModel> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\PaymentModel> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Billing\PeriodModel|null $period
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereBalanceDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereBillingPeriodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereBillingStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereInvoiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereIvaRetenido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceModel withoutTrashed()
 * @mixin \Eloquent
 */
class InvoiceModel extends Model
{
    use HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',            //  Cliente
        'billing_period_id',    //  Período
        'invoice_type',         //  Tipo de factura (individual, consolidada)
        'subtotal',             //  Subtotal
        'iva',                  //  IVA
        'iva_retenido',         //  IVA retenido
        'total_amount',         //  Total
        'paid_amount',          //  Cantidad pagada
        'balance_due',          //  Saldo pendiente
        'billing_status_id',    //  Estado financiero
        'status_id',            //  Estado (activo, inactivo)
        'comments'              //  Observaciones
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'client_id' => 'integer',
        'billing_period_id' => 'integer',
        'invoice_type' => 'integer',
        'billing_status_id' => 'integer',

        'subtotal' => 'decimal:8',
        'iva' => 'decimal:8',
        'iva_retenido' => 'decimal:8',
        'total_amount' => 'decimal:8',
        'paid_amount' => 'decimal:8',
        'balance_due' => 'decimal:8',

        'status_id' => 'boolean',
    ];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PeriodModel::class, 'billing_period_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceDetailModel::class, 'invoice_id', 'id');
    }

    public function financial_status(): BelongsTo
    {
        return $this->belongsTo(StatusModel::class, 'billing_status_id', 'id');
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(InvoiceExtensionModel::class, 'invoice_id', 'id');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            PaymentModel::class,
            'billing_payments_invoices',
            'invoice_id',
            'payment_id',
        )
            ->using(PaymentInvoiceModel::class)
            ->withPivot(['amount_applied'])
            ->withTimestamps()
            ->withTrashed();
    }

    public function dtes(): BelongsToMany
    {
        return $this->belongsToMany(DTEModel::class,
            'accounting_dte_invoices',
            'invoice_id',
            'dte_id')
            ->using(DTEInvoiceModel::class)
            ->withTimestamps();
    }
}
