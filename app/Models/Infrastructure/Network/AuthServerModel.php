<?php

namespace App\Models\Infrastructure\Network;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;
use Illuminate\Support\Facades\Crypt;

/**
 * @property int $id
 * @property string $name
 * @property string $user
 * @property string $secret
 * @property string $ip
 * @property int $port
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthServerModel withoutTrashed()
 * @mixin \Eloquent
 */
class AuthServerModel extends Model
{
    use DataViewer, HasStatusTrait, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'infrastructure_auth_servers';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'user', 'secret', 'ip', 'port', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'ip', 'status_id'];
    protected array $orderable = ['id', 'name', 'ip', 'status_id'];
    protected $appends = ['status'];

    public function setSecretAttribute($val)
    {
        if (!$val) return;

        if ($this->exists && isset($this->attributes['secret'])) {
            try {
                $currentDecrypted = Crypt::decryptString($this->attributes['secret']);

                if ($currentDecrypted === $val) return;

            } catch (\Throwable $e) {
            }
        }

        $this->attributes['secret'] = Crypt::encryptString($val);
    }

    public function getSecretAttribute($val)
    {
        if (!$val) return null;

        try {
            return Crypt::decryptString($val);
        } catch (\Throwable $e) {
            return $val;
        }
    }
}
