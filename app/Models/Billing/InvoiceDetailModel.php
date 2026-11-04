<?php

namespace App\Models\Billing;

use App\Models\Services\ServiceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $invoice_id
 * @property int $service_id
 * @property string $description
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $subtotal
 * @property numeric $iva
 * @property numeric $iva_retenido
 * @property numeric $total
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Billing\InvoiceModel|null $invoice
 * @property-read ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereIvaRetenido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceDetailModel withoutTrashed()
 * @mixin \Eloquent
 */
class InvoiceDetailModel extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_invoice_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'invoice_id',
        'service_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
        'iva',
        'iva_retenido',
        'total',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'unit_price' => 'decimal:8',
        'subtotal' => 'decimal:8',
        'iva' => 'decimal:8',
        'iva_retenido' => 'decimal:8',
        'total' => 'decimal:8',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }
}
