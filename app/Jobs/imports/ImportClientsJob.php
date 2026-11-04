<?php

namespace App\Jobs\imports;

use App\Services\v1\imports\ImportClientsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *  Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     *  Execute the job.
     * @return void
     * @throws \Throwable
     */
    public function handle(ImportClientsService $importClientsService): void
    {
        $importClientsService->importClients();
    }
}
