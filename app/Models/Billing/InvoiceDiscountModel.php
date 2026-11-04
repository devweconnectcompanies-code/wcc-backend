<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDiscountModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDiscountModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDiscountModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDiscountModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDiscountModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDiscountModel withoutTrashed()
 * @mixin \Eloquent
 */
class InvoiceDiscountModel extends Pivot
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_invoice_discounts';
    protected $fillable = [
        'invoice_id',
        'discount_id',
        'applied_amount',
    ];
    protected $casts = [
        'applied_amount' => 'decimal:8',
    ];
}
