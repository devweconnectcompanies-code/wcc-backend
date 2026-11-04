<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property \Illuminate\Support\Carbon $period_start
 * @property \Illuminate\Support\Carbon $period_end
 * @property \Illuminate\Support\Carbon $issue_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon $cutoff_date
 * @property bool $is_active
 * @property bool $is_closed
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property bool $status_id
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Billing\InvoiceModel> $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereCutoffDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereIsClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodModel withoutTrashed()
 * @mixin \Eloquent
 */
class PeriodModel extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_periods';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',             //  Nombre (diciembre 2025)
        'code',             //  Código
        'period_start',     //  Inicio de período
        'period_end',       //  Fin del período
        'issue_date',       //  Fecha de emisión
        'due_date',         //  Fecha de vencimiento
        'cutoff_date',      //  Fecha de corte
        'is_active',        //  Período activo
        'is_closed',        //  Período procesado
        'closed_at',        //  Fecha de cierre del período
        'status_id',        //  Estado del período
        'comments',         //  Comentarios
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'cutoff_date' => 'date',
        'closed_at' => 'datetime',
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
        'status_id' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceModel::class, 'billing_period_id', 'id');
    }
}
