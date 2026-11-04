<?php

namespace App\Models\Supports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $support_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Supports\SupportModel|null $support
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereSupportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogModel whereUserId($value)
 * @mixin \Eloquent
 */
class LogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'supports_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['support_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array'
    ];

    public function support(): BelongsTo
    {
        return $this->belongsTo(SupportModel::class, 'support_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
