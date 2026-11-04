<?php

namespace App\Jobs\billing;

use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\PrepaymentModel;
use App\Services\v1\management\billing\PrepaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApplyPrepaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries = 3;

    /**
     *  Create a new job instance.
     * @param int|null $clientId
     * @param bool $dryRun
     */
    public function __construct(
        private ?int $clientId = null,
        private bool $dryRun = false,
    )
    {
        //
    }

    /**
     *  Execute the job.
     * @param PrepaymentService $service
     * @return void
     */
    public function handle(PrepaymentService $service): void
    {
        PrepaymentModel::query()
            ->select('client_id')
            ->selectRaw('SUM(remaining_amount) as total_remaining')
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->where('remaining_amount', '>', 0)
            ->when($this->clientId, fn($q) => $q->where('client_id', $this->clientId))
            ->groupBy('client_id')
            ->havingRaw('SUM(remaining_amount) > 0')
            ->orderBy('client_id')
            ->chunkById(50, function ($clients) use ($service) {

                foreach ($clients as $client) {
                    if ($this->dryRun) {
                        continue;
                    }

                    $service->applyPrepaymentsToInvoices($client->client_id);
                }
            }, 'client_id');
    }
}
