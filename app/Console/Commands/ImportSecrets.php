<?php

namespace App\Console\Commands;

use App\Jobs\imports\ImportPPPSecretsJob;
use Illuminate\Console\Command;

class ImportSecrets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pppoe-secrets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa a la RB de desarrollo los secrets de los clientes en la tabla internet_services';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Importando PPP a RB desarrollo...");
        ImportPPPSecretsJob::dispatch();
    }
}
