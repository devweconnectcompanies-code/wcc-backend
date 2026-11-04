<?php

namespace App\Console\Commands;

use App\Jobs\clients\NotifyExpiringContractsJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('background:notify-expiring-contracts {--days=15 : Días de anticipación para considerar un contrato próximo a vencer}')]
#[Description('Notifica por Telegram los contratos de clientes que están próximos a vencer.')]
class NotifyExpiringContracts extends Command
{
    public function handle(): int
    {
        $days = (int)$this->option('days');
        NotifyExpiringContractsJob::dispatch($days);
        $this->info("Job de notificación de contratos próximos a vencer enviado a la cola.");
        return self::SUCCESS;
    }
}
