<?php

namespace App\Models\Clients\Logs;

use App\Models\Clients\ClientModel;
use App\Models\Clients\DocumentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $document_id
 * @property int $client_id
 * @property int $user_id
 * @property string $action
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ClientModel|null $client
 * @property-read DocumentModel|null $document
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentLogModel whereUserId($value)
 * @mixin \Eloquent
 */
class DocumentLogModel extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'clients_documents_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['document_id', 'client_id', 'user_id', 'action', 'before', 'after'];
    protected $hidden = ['updated_at'];
    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
