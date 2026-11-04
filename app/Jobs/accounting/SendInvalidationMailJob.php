<?php

namespace App\Jobs\accounting;

use App\Mail\DTE\SendCancelDTEMail;
use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use App\Services\v1\management\accounting\DTE\DTEPrintService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvalidationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly DTEEventModel $cancelDte,
        private readonly DTEModel      $dteModel,
        private readonly string        $recipientEmail,
        private readonly string        $jsonContent,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public
    function handle(DTEPrintService $printService): void
    {
        $pdfOutput = $printService->print($this->dteModel->id)->output();

        Mail::to($this->recipientEmail)
            ->send(new SendCancelDTEMail(
                invalidation: $this->cancelDte,
                originalDte: $this->dteModel,
                pdfOutput: $pdfOutput,
                jsonContent: $this->jsonContent,
            ));
    }
}
