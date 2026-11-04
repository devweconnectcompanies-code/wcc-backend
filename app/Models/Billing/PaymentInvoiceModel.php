<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $payment_id
 * @property int $invoice_id
 * @property numeric $amount_applied
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Billing\DiscountModel|null $discount
 * @property-read \App\Models\Billing\InvoiceModel|null $invoice
 * @property-read \App\Models\Billing\PaymentModel|null $payment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel whereAmountApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInvoiceModel withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentInvoiceModel extends Pivot
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_payments_invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'payment_id',       //  Id pago
        'invoice_id',       //  Id factura
        'amount_applied',   //  Cantidad pagada
    ];
    protected $casts = [
        'amount_applied' => 'decimal:2',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentModel::class, 'payment_id', 'id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id', 'id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(DiscountModel::class, 'discount_id', 'id');
    }
}
