<?php

namespace App\Console\Commands;

use App\Enums\v1\General\CommonStatus;
use App\Jobs\monitoring\SyncConnectionsJob;
use App\Models\Infrastructure\Network\AuthServerModel;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

#[Signature('mikrotik:sync-connections')]
#[Description('Actualiza las conexiones activas PPPoe en la base de datos.')]
class SyncMikrotikActiveConnections extends Command
{
    /**
     * Execute the console command.
     * @return int
     * @throws \Throwable
     */
    public function handle(): int
    {
        $authServers = AuthServerModel::query()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->get(['id', 'name']);

        if ($authServers->isEmpty()) {
            $this->warn("No hay equipos Mikrotik activos configurados.");
            return self::SUCCESS;
        }

        $jobs = $authServers->map(fn(AuthServerModel $authServer) => new SyncConnectionsJob($authServer->id));

        Bus::batch($jobs)
            ->name('mikrotik:sync-connections')
            ->onQueue('mikrotik-sync')
            ->allowFailures()
            ->dispatch();

        $this->info("Se despacharon {$authServers->count()} jobs de sincronización a la cola");
        return self::SUCCESS;
    }
}
