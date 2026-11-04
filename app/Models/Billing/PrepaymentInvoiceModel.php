<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $prepayment_id
 * @property int $invoice_id
 * @property numeric $amount_applied
 * @property \Illuminate\Support\Carbon $applied_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Billing\InvoiceModel|null $invoice
 * @property-read \App\Models\Billing\PrepaymentModel|null $prepayment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereAmountApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereAppliedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel wherePrepaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentInvoiceModel withoutTrashed()
 * @mixin \Eloquent
 */
class PrepaymentInvoiceModel extends Pivot
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_prepayment_invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'prepayment_id',
        'invoice_id',
        'amount_applied',
        'applied_at',
    ];
    protected $casts = [
        'amount_applied' => 'decimal:2',
        'applied_at' => 'datetime',
    ];

    public function prepayment(): BelongsTo
    {
        return $this->belongsTo(PrepaymentModel::class, 'prepayment_id', 'id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id', 'id');
    }
}
