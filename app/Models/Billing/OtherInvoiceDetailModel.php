<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $other_invoice_id
 * @property string $description
 * @property int $item_type
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $subtotal
 * @property numeric $iva
 * @property numeric $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Billing\OtherInvoiceModel|null $invoice
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereOtherInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtherInvoiceDetailModel withoutTrashed()
 * @mixin \Eloquent
 */
class OtherInvoiceDetailModel extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_other_invoice_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'other_invoice_id',
        'description',
        'item_type',
        'quantity',
        'unit_price',
        'subtotal',
        'iva',
        'total',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:8',
        'subtotal' => 'decimal:8',
        'iva' => 'decimal:8',
        'total' => 'decimal:8',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(OtherInvoiceModel::class, 'other_invoice_id', 'id');
    }
}
