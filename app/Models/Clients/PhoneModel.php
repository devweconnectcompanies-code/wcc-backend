<?php

namespace App\Models\Clients;

use App\Models\Configuration\Clients\PhoneTypeModel;
use App\Models\Configuration\Geography\CountryModel;
use App\Observers\Clients\PhoneObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

#[ObservedBy(PhoneObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property int $phone_type_id
 * @property string $number
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $country_code
 * @property-read \App\Models\Clients\ClientModel|null $client
 * @property-read CountryModel|null $country
 * @property-read array $status
 * @property-read PhoneTypeModel|null $phone_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel wherePhoneTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneModel withoutTrashed()
 * @mixin \Eloquent
 */
class PhoneModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'clients_phones';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id', 'phone_type_id', 'number', 'status_id', 'country_code'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'client_id', 'phone_type_id', 'number', 'status_id', 'country_code'];
    protected array $orderable = ['id', 'client_id', 'phone_type_id', 'number', 'status_id', 'country_code'];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function phone_type(): BelongsTo
    {
        return $this->belongsTo(PhoneTypeModel::class, 'phone_type_id', 'id');
    }

    public function country(): HasOne
    {
        return $this->hasOne(CountryModel::class, 'iso_2', 'country_code');
    }
}
