<?php

namespace App\Models\Accounting;

use App\Models\Billing\InvoiceModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $dte_id
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Accounting\DTEModel|null $dte
 * @property-read InvoiceModel|null $invoice
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel whereDteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DTEInvoiceModel withoutTrashed()
 * @mixin \Eloquent
 */
class DTEInvoiceModel extends Pivot
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'accounting_dte_invoices';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['dte_id', 'invoice_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function dte(): BelongsTo
    {
        return $this->belongsTo(DTEModel::class, 'dte_id', 'id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id', 'id');
    }
}
