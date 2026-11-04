<?php

namespace App\Models\Billing\Options;

use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $badge_color
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethodModel withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentMethodModel extends Model
{
    use DataViewer, HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_payment_methods';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'code',
        'badge_color',
        'status_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'code',
        'status_id',
    ];
    protected array $orderable = [
        'id',
        'name',
        'code',
        'status_id',
    ];
    protected $appends = ['status'];
}
