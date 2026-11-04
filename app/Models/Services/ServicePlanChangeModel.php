<?php

namespace App\Models\Services;

use App\Models\Management\Profiles\InternetModel;
use App\Observers\Services\ServicePlanChangeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([ServicePlanChangeObserver::class])]
/**
 * @property int $id
 * @property int $service_id
 * @property int $old_internet_profile_id
 * @property int $new_internet_profile_id
 * @property \Illuminate\Support\Carbon $change_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read InternetModel|null $new_internet_profile
 * @property-read InternetModel|null $old_internet_profile
 * @property-read \App\Models\Services\ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereChangeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereNewInternetProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereOldInternetProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServicePlanChangeModel withoutTrashed()
 * @mixin \Eloquent
 */
class ServicePlanChangeModel extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'services_plan_changes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_id',
        'old_internet_profile_id',
        'new_internet_profile_id',
        'change_date',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'change_date' => 'date',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function old_internet_profile(): BelongsTo
    {
        return $this->belongsTo(InternetModel::class, 'old_internet_profile_id', 'id');
    }

    public function new_internet_profile(): BelongsTo
    {
        return $this->belongsTo(InternetModel::class, 'new_internet_profile_id', 'id');
    }
}
