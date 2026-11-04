<?php

namespace App\Console\Commands;

use App\Jobs\imports\ImportClientsJob;
use Illuminate\Console\Command;

class ImportClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa clientes desde archivo .xlsx';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Importando clientes desde archivo .xlsx");
        ImportClientsJob::dispatch();
    }
}
