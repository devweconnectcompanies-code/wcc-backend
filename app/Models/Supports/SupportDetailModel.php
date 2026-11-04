<?php

namespace App\Models\Supports;

use App\Models\Management\Profiles\InternetModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;

/**
 * @property int $id
 * @property int $support_id
 * @property int $type_id
 * @property int|null $internet_profile_id
 * @property int|null $node_id
 * @property int|null $equipment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read InternetModel|null $profile
 * @property-read \App\Models\Supports\SupportModel|null $support
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereEquipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereInternetProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereSupportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportDetailModel withoutTrashed()
 * @mixin \Eloquent
 */
class SupportDetailModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'supports_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'support_id',
        'type_id',
        'internet_profile_id',
        'node_id',
        'equipment_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'type_id',
        'internet_profile_id',
        'node_id',
        'equipment_id',
    ];
    protected array $orderable = [
        'id',
        'type_id',
        'internet_profile_id',
        'node_id',
        'equipment_id',
    ];

    public function support(): BelongsTo
    {
        return $this->belongsTo(SupportModel::class, 'support_id', 'id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(InternetModel::class, 'internet_profile_id', 'id');
    }
}
