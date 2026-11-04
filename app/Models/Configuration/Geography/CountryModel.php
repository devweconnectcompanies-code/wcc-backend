<?php

namespace App\Models\Configuration\Geography;

use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $es_name
 * @property string $en_name
 * @property string $iso_2
 * @property string $iso_3
 * @property int $phone_prefix
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereEsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereIso2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel wherePhonePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryModel withoutTrashed()
 * @mixin \Eloquent
 */
class CountryModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'config_countries';
    protected $primaryKey = 'id';
    protected $fillable = ['es_name', 'en_name', 'iso_2', 'iso_3', 'phone_prefix', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'es_name', 'en_name', 'iso_2', 'iso_3', 'phone_prefix', 'status_id'];
    protected array $orderable = ['id', 'es_name', 'en_name', 'status_id'];
}
