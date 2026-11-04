<?php

namespace App\Models\Billing;

use App\Enums\v1\General\BillingStatus;
use App\Models\Billing\Options\StatusModel;
use App\Models\Clients\ClientModel;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $client_id
 * @property numeric $current_balance
 * @property numeric $overdue_balance
 * @property numeric $total_paid_amount
 * @property int $total_invoices
 * @property int $paid_invoices
 * @property int $pending_invoices
 * @property int $overdue_invoices
 * @property BillingStatus $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property numeric $prepayment_balance
 * @property-read ClientModel|null $client
 * @property-read StatusModel|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereOverdueBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereOverdueInvoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel wherePaidInvoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel wherePendingInvoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel wherePrepaymentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereTotalInvoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereTotalPaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFinancialStatusModel withoutTrashed()
 * @mixin \Eloquent
 */
class ClientFinancialStatusModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'clients_financial_status';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id',            //  Id de Cliente
        'current_balance',      //  Saldo de facturas que no sean pagadas
        'overdue_balance',      //  Saldo vencido
        'total_paid_amount',    //  Cantidad pagada
        'prepayment_balance',   //  Cantidad abonada
        'total_invoices',       //  Facturas totales generadas
        'paid_invoices',        //  Facturas totales pagadas
        'pending_invoices',     //  Facturas pendientes
        'overdue_invoices',     //  Facturas vencidas
        'status_id',            //  Estado financiero
    ];
    protected $allowedFilters = [
        'id',
        'client_id',
        'status_id',
    ];
    protected $orderable = ['id', 'client_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'client_id' => 'integer',
        'current_balance' => 'decimal:8',
        'overdue_balance' => 'decimal:8',
        'total_paid_amount' => 'decimal:8',
        'prepayment_balance' => 'decimal:8',
        'total_invoices' => 'integer',
        'pending_invoices' => 'integer',
        'overdue_invoices' => 'integer',
        'status_id' => BillingStatus::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }
}
