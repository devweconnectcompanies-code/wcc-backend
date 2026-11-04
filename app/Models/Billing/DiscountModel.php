<?php

namespace App\Models\Billing;

use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property numeric|null $percentage
 * @property numeric|null $amount
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @property-read \App\Models\Billing\InvoiceDiscountModel|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\InvoiceModel> $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountModel withoutTrashed()
 * @mixin \Eloquent
 */
class DiscountModel extends Model
{
    use SoftDeletes, HasStatusTrait, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'billing_discounts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',             //  Nombre
        'code',             //  Código
        'description',      //  Descripción
        'percentage',       //  Porcentaje
        'amount',           //  Monto fijo
        'status_id'         //  Estado
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'code',
        'percentage',
        'amount',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'name',
        'code',
        'percentage',
        'amount',
        'status_id',
    ];
    protected $casts = [
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2'
    ];
    protected $appends = ['status'];

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(
            InvoiceModel::class,
            'billing_invoice_discounts',
            'discount_id',
            'invoice_id'
        )
            ->using(InvoiceDiscountModel::class)
            ->withPivot(['applied_amount'])
            ->withTimestamps()
            ->withTrashed();
    }
}
