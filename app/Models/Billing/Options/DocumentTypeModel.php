<?php

namespace App\Models\Billing\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasStatusTrait;
use App\Traits\DataViewer;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentTypeModel withoutTrashed()
 * @mixin \Eloquent
 */
class DocumentTypeModel extends Model
{
    use HasStatusTrait, DataViewer, SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'billing_document_types';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'code', 'status_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = ['id', 'name', 'code', 'status_id'];
    protected array $orderable = ['id', 'name', 'code', 'status_id'];
    protected $appends = ['status'];
}
