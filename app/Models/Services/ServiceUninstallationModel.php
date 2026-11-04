<?php

namespace App\Models\Services;

use App\Models\Management\Profiles\InternetModel;
use App\Observers\Services\ServiceUninstallationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([ServiceUninstallationObserver::class])]
/**
 * @property int $id
 * @property int $service_id
 * @property int $internet_profile_id
 * @property \Illuminate\Support\Carbon $uninstallation_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read InternetModel|null $profile
 * @property-read \App\Models\Services\ServiceModel|null $service
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereInternetProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereUninstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceUninstallationModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServiceUninstallationModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'services_uninstallations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'service_id',
        'internet_profile_id',
        'uninstallation_date',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = ['uninstallation_date' => 'date'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(InternetModel::class, 'internet_profile_id', 'id');
    }
}
