<?php

namespace App\Models\Clients;

use App\Observers\Clients\EmailObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

#[ObservedBy(EmailObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property string $email
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Clients\ClientModel|null $client
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailModel withoutTrashed()
 * @mixin \Eloquent
 */
class EmailModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'clients_emails';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id', 'email', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'client_id', 'email', 'status_id'];
    protected array $orderable = ['id', 'client_id', 'email', 'status_id'];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }
}
