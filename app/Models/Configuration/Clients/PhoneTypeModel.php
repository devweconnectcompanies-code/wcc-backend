<?php

namespace App\Models\Configuration\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property string $name
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhoneTypeModel withoutTrashed()
 * @mixin \Eloquent
 */
class PhoneTypeModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'config_phone_types';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'status_id'];
    protected array $orderable = ['id', 'name', 'status_id'];
    protected $appends = ['status'];
}
