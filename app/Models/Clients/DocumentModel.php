<?php

namespace App\Models\Clients;

use App\Models\Configuration\Clients\DocumentTypeModel;
use App\Observers\Clients\DocumentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DataViewer;
use App\Traits\HasStatusTrait;

#[ObservedBy(DocumentObserver::class)]
/**
 * @property int $id
 * @property int $client_id
 * @property int $document_type_id
 * @property string $number
 * @property string $expiration_date
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Clients\ClientModel|null $client
 * @property-read DocumentTypeModel|null $document_type
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereDocumentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentModel withoutTrashed()
 * @mixin \Eloquent
 */
class DocumentModel extends Model
{
    use SoftDeletes, DataViewer, HasStatusTrait;

    protected $connection = 'pgsql';
    protected $table = 'clients_documents';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id', 'document_type_id', 'number', 'expiration_date', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'client_id', 'document_type_id', 'number', 'status_id'];
    protected array $orderable = ['id', 'client_id', 'document_type_id', 'number', 'status_id'];
    protected $appends = ['status'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentTypeModel::class, 'document_type_id', 'id');
    }
}
