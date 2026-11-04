<?php

namespace App\Jobs\billing;

use App\Services\v1\management\billing\background\OverdueServiceCutService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CutOverdueServicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public bool $dryRun;

    /**
     *  Create a new job instance.
     * @param bool $dryRun
     */
    public function __construct(bool $dryRun = false)
    {
        $this->dryRun = $dryRun;
    }

    /**
     *  Execute the job.
     * @param OverdueServiceCutService $service
     * @return void
     */
    public function handle(OverdueServiceCutService $service): void
    {
        $service->cutOverdueClients($this->dryRun);
    }
}
