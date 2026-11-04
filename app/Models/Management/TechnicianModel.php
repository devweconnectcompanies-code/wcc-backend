<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

/**
 * @property int $id
 * @property int $user_id
 * @property string $phone_number
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $hiring_date
 * @property-read array $status
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereHiringDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechnicianModel withoutTrashed()
 * @mixin \Eloquent
 */
class TechnicianModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'technicians';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'phone_number', 'hiring_date', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'user_id', 'status_id', 'user.name'];
    protected array $orderable = ['id', 'user_id', 'status_id'];
    protected $appends = ['status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
