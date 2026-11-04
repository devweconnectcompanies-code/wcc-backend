<?php

namespace App\Models\Management\Profiles;

use App\Models\Services\ServiceInternetModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property string $mk_profile
 * @property string|null $debt_profile
 * @property numeric $net_value
 * @property numeric $iva
 * @property numeric $price
 * @property string $expiration_date
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property bool $iptv
 * @property bool $ftth
 * @property int $allowed_stb
 * @property-read array $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ServiceInternetModel> $service_internet
 * @property-read int|null $service_internet_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereAllowedStb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereDebtProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereFtth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereIptv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereMkProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereNetValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternetModel withoutTrashed()
 * @mixin \Eloquent
 */
class InternetModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'management_internet_profiles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'alias',
        'description',
        'mk_profile',
        'debt_profile',
        'net_value',
        'iva',
        'price',
        'expiration_date',
        'status_id',
        'iptv',
        'ftth',
        'allowed_stb',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'name',
        'alias',
        'mk_profile',
        'price',
        'status_id',
        'iptv',
        'ftth',
        'allowed_stb',
    ];
    protected array $orderable = [
        'id',
        'name',
        'alias',
        'price',
        'status_id',
        'iptv',
        'ftth',
        'allowed_stb',
    ];
    protected $appends = ['status'];

    public function service_internet(): HasMany
    {
        return $this->hasMany(ServiceInternetModel::class, 'internet_profile_id', 'id');
    }
}
