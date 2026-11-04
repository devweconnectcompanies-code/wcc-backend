<?php

namespace App\Models\Monitoring;

use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceInternetModel;
use App\Models\Services\ServiceModel;
use App\Traits\DataViewer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Pest\Laravel\get;

/**
 * @property int $id
 * @property int|null $internet_service_id
 * @property string $pppoe_user
 * @property string|null $ip_address
 * @property string|null $caller_id
 * @property string|null $uptime
 * @property int|null $uptime_seconds
 * @property string|null $mikrotik_ref_id
 * @property \Illuminate\Support\Carbon|null $last_synced_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $client_id
 * @property int|null $service_id
 * @property-read ClientModel|null $client
 * @property-read ServiceInternetModel|null $internet_service
 * @property-read ServiceModel|null $service
 * @property-read string $uptime_human
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel advancedFilter()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereCallerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereInternetServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereLastSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereMikrotikRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel wherePppoeUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereUptime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel whereUptimeSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActiveConnectionModel withoutTrashed()
 * @mixin \Eloquent
 */
class ActiveConnectionModel extends Model
{
    use SoftDeletes, DataViewer;

    protected $connection = 'pgsql';
    protected $table = 'internet_active_connections';
    protected $fillable = [
        'internet_service_id',
        'pppoe_user',
        'ip_address',
        'caller_id',
        'uptime',
        'uptime_seconds',
        'mikrotik_ref_id',
        'last_synced_at',
        'client_id',
        'service_id',
    ];
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected array $allowedFilters = [
        'id',
        'pppoe_user',
        'ip_address',
        'caller_id',
        'uptime',
        'uptime_seconds',
        'mikrotik_ref_id',
        'client_id',
        'service_id',

        'client.name',
        'client.surname',
        'client.dui.number',

//        'internet_service.service.client.name',
//        'internet_service.service.client.surname',
//        'internet_service.service.client.dui.number',
    ];
    protected array $orderable = [
        'id',
        'pppoe_user',
        'ip_address',
        'caller_id',
        'uptime',
        'uptime_seconds',
        'mikrotik_ref_id',
    ];
    protected $casts = ['last_synced_at' => 'datetime'];

    protected $appends = ['uptime_human'];

    public function internet_service(): BelongsTo
    {
        return $this->belongsTo(ServiceInternetModel::class, 'internet_service_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    protected function uptimeHuman(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->formatUptime($this->uptime_seconds ?? 0),
        );
    }

    private function formatUptime(int $totalSeconds): string
    {
        if ($totalSeconds <= 0) return '0 segundos';

        $units = [
            'mes' => 2592000,
            'día' => 86400,
            'hora' => 3600,
            'minuto' => 60,
            'segundo' => 1,
        ];

        $plurals = [
            'mes' => 'meses',
            'día' => 'días',
            'hora' => 'horas',
            'minuto' => 'minutos',
            'segundo' => 'segundos',
        ];

        $remaining = $totalSeconds;
        $parts = [];

        foreach ($units as $label => $secondsInUnit) {
            $value = intdiv($remaining, $secondsInUnit);

            if ($value > 0) {
                $parts[] = $value . ' ' . ($value === 1 ? $label : $plurals[$label]);
                $remaining -= $value * $secondsInUnit;
            }
        }
        return implode(', ', $parts);
    }
}
