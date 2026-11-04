<?php

namespace App\Models\Clients;

use App\Models\Configuration\Clients\KinshipModel;
use App\Observers\Clients\ReferenceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ReferenceObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $dui
 * @property string $mobile
 * @property int $kinship_id
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Clients\ClientModel|null $client
 * @property-read array $status
 * @property-read KinshipModel|null $kinship
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereDui($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereKinshipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferenceModel withoutTrashed()
 * @mixin \Eloquent
 */
class ReferenceModel extends Model
{
    use DataViewer, HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'clients_references';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id', 'name', 'dui', 'mobile', 'kinship_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'client_id', 'name', 'dui', 'mobile', 'kinship_id', 'status_id'];
    protected array $orderable = ['id', 'client_id', 'name', 'dui', 'mobile', 'kinship_id', 'status_id'];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function kinship(): HasOne
    {
        return $this->hasOne(KinshipModel::class, 'id', 'kinship_id');
    }
}
