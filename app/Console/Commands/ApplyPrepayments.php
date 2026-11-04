<?php

namespace App\Console\Commands;

use App\Jobs\billing\ApplyPrepaymentJob;
use Illuminate\Console\Command;

class ApplyPrepayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:apply-prepayments
                                {--client= : ID del cliente}
                                {--dry-run : Simular sin aplicar cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aplica los abonos disponibles a las facturas pendientes de los clientes.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando aplicación de abonos...');

        ApplyPrepaymentJob::dispatch(
            clientId: $this->option('client'),
            dryRun: $this->option('dry-run'),
        )/*->onQueue('billing')*/
        ;

        $this->info('Job enviado a la cola.');

        return self::SUCCESS;
    }
}
