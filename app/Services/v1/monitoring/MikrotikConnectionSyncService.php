<?php

namespace App\Services\v1\monitoring;

use App\Libraries\MikrotikAPI;
use App\Models\Monitoring\ActiveConnectionModel;
use App\Models\Services\ServiceInternetModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class MikrotikConnectionSyncService
{
    public function __construct(private readonly MikrotikAPI $api)
    {
    }

    /**
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int $port
     * @return array
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     * @throws \Throwable
     */
    public function sync(string $host, string $user, string $pass, int $port = 45000): array
    {
        $connections = $this->api->getActivePPPConnections(host: $host, user: $user, pass: $pass, port: $port);
        Log::channel('monitoring_sync')->info("[MONITORING] Respuesta de /ppp/active/print", [
            'count' => count($connections),
            'sample' => $connections[0] ?? null,
        ]);
        $syncedUsers = [];

        DB::transaction(function () use ($connections, &$syncedUsers) {
            foreach ($connections as $connection) {
                $pppoeUser = $connection['name'] ?? null;

                if (!$pppoeUser) continue;

                $internetService = ServiceInternetModel::query()
                    ->with('service')
                    ->where('user', $pppoeUser)
                    ->first();
                $uptime = $connection['uptime'] ?? null;

                ActiveConnectionModel::query()
                    ->updateOrCreate(
                        ['pppoe_user' => trim($pppoeUser)],
                        [
                            'internet_service_id' => $internetService?->id,
                            'ip_address' => $connection['address'] ?? null,
                            'caller_id' => $connection['caller-id'] ?? null,
                            'uptime' => $uptime,
                            'uptime_seconds' => $uptime ? $this->parseUptimeToSeconds($uptime) : null,
                            'mikrotik_ref_id' => $connection['.id'] ?? null,
                            'last_synced_at' => now(),
                            'client_id' => $internetService?->service?->client_id,
                            'service_id' => $internetService?->service?->id,
                        ]
                    );
                $syncedUsers[] = $pppoeUser;
            }

            //  Elimina las conexiones que ya no están activas en el AUTH SERVER.
//            ActiveConnectionModel::query()
//                ->whereNotIn('pppoe_user', $syncedUsers)
//                ->delete();
        });

        return $syncedUsers;
    }

    /**
     * Formatea el uptime retornado por RouterOS a segundos.
     *
     * @param string $uptime
     * @return int
     */
    private function parseUptimeToSeconds(string $uptime): int
    {
        // RouterOS formatea así: "1w2d3h4m5s", "4h5m6s", "5m6s", "6s"
        preg_match_all('/(\d+)([wdhms])/', $uptime, $matches, PREG_SET_ORDER);

        $seconds = 0;
        foreach ($matches as [, $value, $unit]) {
            $seconds += match ($unit) {
                'w' => $value * 604800,
                'd' => $value * 86400,
                'h' => $value * 3600,
                'm' => $value * 60,
                's' => (int)$value,
            };
        }

        return $seconds;
    }

    /**
     * Busca y retorna directamente desde la RB, los datos de una sesión PPPoE activa: conexión,
     * perfil asignado y tráfico actual.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $pppoeUser
     * @param int $port
     * @return array|null
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     */
    public function findActiveConnectionByUser(
        string $host,
        string $user,
        string $pass,
        string $pppoeUser,
        int    $port = 45000,
    ): ?array
    {
        $pppoeUser = trim($pppoeUser);
        $details = $this->api->getActiveConnectionDetails(
            host: $host,
            user: $user,
            pass: $pass,
            pppoeUser: $pppoeUser,
            port: $port,
        );

        if (empty($details['active'])) return null;
        $connection = $details['active'];
        $secret = $details['secret'];
        $traffic = $details['traffic'];
        $uptime = $connection['uptime'] ?? null;

        return [
            'pppoe_user' => $connection['name'] ?? $pppoeUser,
            'ip_address' => $connection['address'] ?? null,
            'caller_id' => $connection['caller-id'] ?? null,
            'uptime' => $uptime,
            'mikrotik_ref_id' => $connection['.id'] ?? null,
            'profile' => $secret['profile'] ?? null,
            'service' => $connection['service'] ?? null,
            'traffic' => $traffic
                ? [
                    'rx_bps' => isset($traffic['rx-bits-per-second']) ? (int)$traffic['rx-bits-per-second'] : null,
                    'tx_bps' => isset($traffic['tx-bits-per-second']) ? (int)$traffic['tx-bits-per-second'] : null,
                    'rx_packets_per_second' => isset($traffic['rx-packets-per-second']) ? (int)$traffic['rx-packets-per-second'] : null,
                    'tx_packets_per_second' => isset($traffic['tx-packets-per-second']) ? (int)$traffic['tx-packets-per-second'] : null,
                ] : null,
            'fetched_at' => now('America/El_Salvador')->toDateTimeString(),
        ];
    }
}
