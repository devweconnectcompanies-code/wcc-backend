<?php

namespace App\Models\Services;

use App\Models\Management\Profiles\InternetModel;
use App\Models\Monitoring\ActiveConnectionModel;
use App\Models\Services\ServiceModel;
use App\Observers\Services\InternetServiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

#[ObservedBy(InternetServiceObserver::class)]
/**
 * @property int $id
 * @property int $internet_profile_id
 * @property int $service_id
 * @property string $user
 * @property string $secret
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read ActiveConnectionModel|null $active_connections
 * @property-read array $status
 * @property-read InternetModel|null $profile
 * @property-read ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereInternetProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceInternetModel withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceInternetModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'internet_services';
    protected $primaryKey = 'id';
    protected $fillable = [
        'internet_profile_id',
        'service_id',
        'user',
        'secret',
        'status_id'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $allowedFilters = [
        'id',
        'internet_profile_id',
        'service_id',
        'user',
        'secret',
        'status_id'
    ];
    protected $orderable = [
        'id',
        'internet_profile_id',
        'service_id',
        'user',
        'secret',
        'status_id'
    ];
    protected $appends = ['status'];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(InternetModel::class, 'internet_profile_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function active_connections(): HasOne
    {
        return $this->hasOne(ActiveConnectionModel::class, 'internet_service_id', 'id');
    }
}
