<?php

namespace App\Models\Billing;

use App\Models\Accounting\DTEModel;
use App\Models\Billing\Options\PaymentMethodModel;
use App\Models\Clients\ClientModel;
use App\Models\User;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $client_id
 * @property int $payment_method_id
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon $payment_date
 * @property string|null $reference_number
 * @property int $user_id
 * @property string|null $comments
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $discount_id
 * @property numeric|null $discount_amount
 * @property-read ClientModel|null $client
 * @property-read \App\Models\Billing\DiscountModel|null $discount
 * @property-read DTEModel|null $dte
 * @property-read float $effective_amount
 * @property-read array $status
 * @property-read \App\Models\Billing\PaymentInvoiceModel|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\InvoiceModel> $invoices
 * @property-read int|null $invoices_count
 * @property-read PaymentMethodModel|null $payment_method
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentModel withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentModel extends Model
{
    use SoftDeletes, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'billing_payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'reference_number',
        'user_id',
        'comments',
        'status_id',
        'discount_id',
        'discount_amount',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'discount_amount' => 'decimal:2',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['status'];

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(
            InvoiceModel::class,
            'billing_payments_invoices',
            'payment_id',
            'invoice_id'
        )
            ->using(PaymentInvoiceModel::class)
            ->withPivot(['amount_applied'])
            ->withTimestamps()
            ->withTrashed();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodModel::class, 'payment_method_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(DiscountModel::class, 'discount_id', 'id');
    }

    public function dte(): HasOne
    {
        return $this->hasOne(DTEModel::class, 'payment_id', 'id');
    }

    public function getEffectiveAmountAttribute(): float
    {
        return $this->amount + ($this->discount_amount ?? 0);
    }
}
