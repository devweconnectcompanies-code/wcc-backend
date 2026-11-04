<?php

namespace App\Jobs\imports;

use App\Models\Infrastructure\Network\AuthServerModel;
use App\Services\v1\imports\ImportMikrotikPPPService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportPPPSecretsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ImportMikrotikPPPService $service): void
    {
        $server = AuthServerModel::query()->findOrFail(1);

        $service->sync($server->toArray());
    }
}
