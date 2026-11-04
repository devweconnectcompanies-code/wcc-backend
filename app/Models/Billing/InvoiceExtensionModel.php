<?php

namespace App\Models\Billing;

use App\Models\User;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon $previous_due_date
 * @property \Illuminate\Support\Carbon $extended_due_date
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $user_id
 * @property bool $status_id
 * @property-read array $status
 * @property-read \App\Models\Billing\InvoiceModel|null $invoice
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereExtendedDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel wherePreviousDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvoiceExtensionModel withoutTrashed()
 * @mixin \Eloquent
 */
class InvoiceExtensionModel extends Model
{
    use SoftDeletes, HasStatusTrait, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'billing_invoice_extensions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'invoice_id',           //  Id de factura
        'previous_due_date',    //  Fecha de corte anterior
        'extended_due_date',    //  Fecha de vencimiento de prórroga
        'reason',               //  Motivo
        'user_id',              //  Usuario que crea la prórroga
        'status_id',            //  Estado de la prórroga
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'invoice_id',
        'previous_due_date',
        'extended_due_date',
        'user_id',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'invoice_id',
        'previous_due_date',
        'extended_due_date',
        'user_id',
        'status_id',
    ];
    protected $casts = [
        'previous_due_date' => 'date',
        'extended_due_date' => 'date',
    ];
    protected $appends = ['status'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select('id', 'name');
    }
}
