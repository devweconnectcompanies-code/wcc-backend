<?php

namespace App\Services\v1\management\dashboard;

use App\Libraries\MikrotikAPI;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class DashboardMikrotikService
{
    private const CACHE_TTL = [
        'resources' => 4,
        'traffic' => 4,
        'sessions' => 5,
        'interfaces' => 10,
        'leases' => 15,
    ];

    public function __construct(
        private readonly MikrotikAPI $mkApi
    )
    {

    }

    /**
     * Recursos del sistema CPU + Memoria.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     */
    public function getSystemResources(string $host, string $user, string $pass, int $port): array
    {
        $cacheKey = "mikrotik.resources.{$host}";

        return Cache::remember($cacheKey, self::CACHE_TTL['resources'], function () use ($host, $user, $pass, $port) {
            $raw = $this->mkApi->getSystemResources($host, $user, $pass, $port);

            if (empty($raw['resources'])) return [];

            $r = $raw['resources'][0];
            $identity = $raw['identity'][0] ?? [];
            $totalMem = (int)$r['total-memory'];
            $freeMem = (int)$r['free-memory'];
            $usedMem = $totalMem - $freeMem;

            return [
                'cpu' => [
                    'load_pct' => (int)$r['cpu-load'],
                    'count' => (int)$r['cpu-count'] ?? 1,
                    'frequency_mhz' => (int)$r['cpu-frequency'] ?? 0,
                    'load_label' => ($r['cpu-load'] ?? '0') . '%',
                ],
                'memory' => [
                    'total_mb' => $this->bytesToMb($totalMem),
                    'used_mb' => $this->bytesToMb($usedMem),
                    'free_mb' => $this->bytesToMb($freeMem),
                    'used_pct' => $totalMem > 0 ? round(($usedMem / $totalMem) * 100, 2) : 0,
                ],
                'storage' => $this->extractStorage($r),
                'system' => [
                    'name' => $identity['name'] ?? null,
                    'uptime' => $r['uptime'] ?? null,
                    'version' => $r['version'] ?? null,
                    'board_name' => $r['board-name'] ?? null,
                    'platform' => $r['platform'] ?? null,
                    'build_time' => $r['build-time'] ?? null,
                ],
                'timestamp' => now()->toIso8601String(),
            ];
        });
    }

    /**
     * Tráfico instantáneo de una sola interfaz en bps y Mbps.
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param int $port
     * @param string $interface
     * @return array
     */
    public function getInterfaceTraffic(
        string $host,
        string $user,
        string $password,
        int    $port,
        string $interface = 'ether1'
    ): array
    {
        $cacheKey = "mikrotik.traffic.{$host}.{$interface}";

        return Cache::remember($cacheKey, self::CACHE_TTL['traffic'], function () use ($host, $user, $password, $port, $interface) {
            $interfaces = $this->mkApi->getInterfaces(host: $host, user: $user, pass: $password, port: $port);
            $exists = collect($interfaces)->contains('name', $interface);

            if (!$exists) {
                throw ValidationException::withMessages([
                    'interface' => "La interfaz {$interface} no existe en el equipo.",
                ]);
            }
            $raw = $this->mkApi->getInterfaceTraffic(
                host: $host,
                user: $user,
                pass: $password,
                interface: $interface,
                port: $port
            );

            if (empty($raw)) {
                throw ValidationException::withMessages([
                    'interface' => "No se pudo obtener tráfico de la interfaz {$interface}.",
                ]);
            };

            $t = $raw[0];

            return [
                'interface' => $interface,
                'rx' => [
                    'bps' => (int)($t['rx-bits-per-second'] ?? 0),
                    'mbps' => $this->bitsToMbps($t['rx-bits-per-second'] ?? 0),
                    'pps' => (int)($t['rx-packets-per-second'] ?? 0),
                ],
                'tx' => [
                    'bps' => (int)($t['tx-bits-per-second'] ?? 0),
                    'mbps' => $this->bitsToMbps($t['tx-bits-per-second'] ?? 0),
                    'pps' => (int)($t['tx-packets-per-second'] ?? 0),
                ],
                'timestamp' => now()->toIso8601String(),
            ];
        });
    }

    /**
     * Tráfico de multiples interfaces en paralelo (una conexión por interfaz).
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @param array $interfaces
     * @return array
     */
    public function getMultipleInterfacesTraffic(
        string $host,
        string $user,
        string $pass,
        int    $port,
        array  $interfaces = ['ether1']
    ): array
    {
        return collect($interfaces)
            ->mapWithKeys(fn(string $iface) => [
                $iface => $this->getInterfaceTraffic($host, $user, $pass, $port, $iface),
            ])
            ->toArray();
    }

    /**
     * Lista de interfaces con sus contadores acumulados.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     */
    public function getInterfaceList(string $host, string $user, string $pass, int $port): array
    {
        $cacheKey = "mikrotik.interfaces.{$host}";

        return Cache::remember($cacheKey, self::CACHE_TTL['interfaces'], function () use ($host, $user, $pass, $port) {
            $raw = $this->mkApi->getInterfaces($host, $user, $pass, $port);

            return collect($raw)
                ->map(fn($i) => [
                    'name' => $i['name'],
                    'type' => $i['type'] ?? null,
                    'running' => ($i['running'] ?? 'false') === 'true',
                    'disabled' => ($i['disabled'] ?? 'false') === 'true',
                    'rx_bytes' => (int)($i['rx-byte'] ?? 0),
                    'tx_bytes' => (int)($i['tx-byte'] ?? 0),
                    'rx_gb' => $this->bytesToGb((int)($i['rx-byte'] ?? 0)),
                    'tx_gb' => $this->bytesToGb((int)($i['tx-byte'] ?? 0)),
                    'comment' => $i['comment'] ?? null,
                ])
                ->values()
                ->toArray();
        });
    }

    /**
     * Sesiones PPPoE activas con total y detalle por sesión.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     */
    public function getActiveSessions(string $host, string $user, string $pass, int $port): array
    {
//        $connections = $this->mkApi->getActivePPPConnectionsIterator(host: $host, user: $user, pass: $pass, port: $port);
//        dd($connections);

        $cacheKey = "mikrotik.sessions.{$host}";

        return Cache::remember($cacheKey, self::CACHE_TTL['sessions'], function () use ($host, $user, $pass, $port) {
            $raw = $this->mkApi->getActivePPPConnections($host, $user, $pass, $port);

            $sessions = collect($raw)
                ->map(fn($s) => [
                    'name' => $s['name'] ?? null,
                    'service' => $s['service'] ?? null,
                    'caller_id' => $s['caller-id'] ?? null,
                    'address' => $s['address'] ?? null,
                    'uptime' => $s['uptime'] ?? null,
                    'encoding' => $s['encoding'] ?? null,
                ]);

            return [
                'total' => $sessions->count(),
                'sessions' => $sessions->values()->toArray(),
            ];
        });
    }

    /**
     * Snapshot completo - un solo endpoint con toda la data.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @param array $interfaces
     * @return array
     */
    public function getDashboardSnapshot(
        string $host,
        string $user,
        string $pass,
        int    $port,
        array  $interfaces = ['ether1']
    ): array
    {
        return [
            'resources' => $this->getSystemResources(host: $host, user: $user, pass: $pass, port: $port),
            'traffic' => $this->getMultipleInterfacesTraffic(
                host: $host,
                user: $user,
                pass: $pass,
                port: $port,
                interfaces: $interfaces
            ),
            'sessions' => $this->getActiveSessions(host: $host, user: $user, pass: $pass, port: $port),
        ];
    }

    /**
     * @param int $bytes
     * @return float
     */
    private function bytesToMb(int $bytes): float
    {
        return round($bytes / 1_048_576, 2);
    }

    /**
     * @param int $bytes
     * @return float
     */
    private function bytesToGb(int $bytes): float
    {
        return round($bytes / 1_073_741_824, 3);
    }

    /**
     * @param int $bits
     * @return float
     */
    private function bitsToMbps(int $bits): float
    {
        return round($bits / 1_000_000, 3);
    }

    /**
     * Extrae los datos de almacenamiento del bloque de recursos si están disponibles.
     *
     * @param array $r
     * @return array
     */
    private function extractStorage(array $r): array
    {
        $totalHdd = (int)($r['total-hdd-space'] ?? 0);
        $freeHdd = (int)($r['free-hdd-space'] ?? 0);

        if ($totalHdd === 0) return [];

        return [
            'total_mb' => $this->bytesToMb($totalHdd),
            'free_mb' => $this->bytesToMb($freeHdd),
            'used_pct' => round((($totalHdd - $freeHdd) / $totalHdd) * 100, 2),
        ];
    }
}
