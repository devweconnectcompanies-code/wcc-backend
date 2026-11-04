<?php

namespace App\Console\Commands;

use App\Jobs\billing\GenerateInvoicesJob;
use App\Models\Billing\PeriodModel;
use Illuminate\Console\Command;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-invoices
                                {period? : Period code (YYYYMM)}
                                {--all-clients : Generar para todos los clientes}
                                {--stats : Mostrar estadísticas}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar facturas para un período determinado';

    /***
     *  Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        $periodCode = $this->argument('period') ?? now()->format('Ym');
        $period = PeriodModel::query()->where('code', $periodCode)->first();

        if (!$period) {
            $this->error("Período {$periodCode} no encontrado.");
            return;
        }

        if ($period->is_closed) {
            $this->error("El período {$periodCode} está cerrado.");
            return;
        }

        GenerateInvoicesJob::dispatch($period->id, $this->option('all-clients'), $this->option('stats'));

        $this->info("Job para generar facturas enviado a la cola.");
    }
}
