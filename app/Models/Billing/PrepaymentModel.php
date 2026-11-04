<?php

namespace App\Models\Billing;

use App\Models\Billing\Options\PaymentMethodModel;
use App\Models\Clients\ClientModel;
use App\Models\User;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $client_id
 * @property numeric $amount
 * @property numeric $remaining_amount
 * @property int $payment_method_id
 * @property string|null $reference_number
 * @property \Illuminate\Support\Carbon $payment_date
 * @property int $user_id
 * @property string|null $comments
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\PrepaymentInvoiceModel> $applications
 * @property-read int|null $applications_count
 * @property-read ClientModel|null $client
 * @property-read array $status
 * @property-read \App\Models\Billing\PrepaymentInvoiceModel|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\InvoiceModel> $invoices
 * @property-read int|null $invoices_count
 * @property-read PaymentMethodModel|null $payment_method
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereRemainingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrepaymentModel withoutTrashed()
 * @mixin \Eloquent
 */
class PrepaymentModel extends Model
{
    use SoftDeletes, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'billing_prepayments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',
        'amount',
        'remaining_amount',
        'payment_method_id',
        'reference_number',
        'payment_date',
        'user_id',
        'comments',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];
    protected $appends = ['status'];

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

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(
            InvoiceModel::class,
            'billing_prepayment_invoices',
            'prepayment_id',
            'invoice_id'
        )
            ->using(PrepaymentInvoiceModel::class)
            ->withPivot(['amount_applied', 'applied_at'])
            ->withTimestamps()
            ->withTrashed();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(PrepaymentInvoiceModel::class, 'prepayment_id', 'id');
    }
}
