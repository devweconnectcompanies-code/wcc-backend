<?php

namespace App\Console\Commands;

use App\Jobs\billing\CutOverdueServicesJob;
use Illuminate\Console\Command;

class CutOverdueServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:cut-overdue-invoices {--dry-run : Solo mostrar, servicios, no cortar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corta los servicios de los clientes con facturas vencidas sin extensiones';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Despachando job para cortar los servicios de los clientes con facturas vencidas sin extensiones');
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('Modo simulación (dry-run)');
        }

        CutOverdueServicesJob::dispatch($dryRun);

        $this->info('Job enviado a la cola correctamente.');

        return Command::SUCCESS;
    }
}
