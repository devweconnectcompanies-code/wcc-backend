<?php

namespace App\Mail\DTE;

use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCancelDTEMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly DTEEventModel $invalidation,
        public readonly DTEModel      $originalDte,
        public readonly string        $pdfOutput,
        public readonly string        $jsonContent,
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Anulación de Documento Tributario Electrónico - {$this->originalDte->generation_code}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'v1.management.mails.dte.dte_invalidation_mail',
            with: [
                'cancelDte' => $this->invalidation,
                'originalDte' => $this->originalDte,
                'clientName' => $this->resolveClientName(),
                'dteTypeName' => $this->originalDte->dte_type?->name ?? 'Documento Tributario Electrónico',
                'generatedAt' => Carbon::parse($this->invalidation->generation_datetime)
                    ->locale('es')
                    ->isoFormat('dddd D [de] MMMM [del] YYYY [a las] HH:mm'),
                'uri' => "https://admin.factura.gob.sv/consultaPublica?ambiente=01&codGen={$this->invalidation->generation_code}&fechaEmi={$this->getDate($this->invalidation->generation_datetime)}"

            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $filename = $this->buildFilename();

        return [
            Attachment::fromData(
                fn() => $this->pdfOutput,
                "{$filename}.pdf"
            )->withMime('application/pdf'),

            Attachment::fromData(
                fn() => $this->jsonContent,
                "{$filename}.json"
            )->withMime('application/json')
        ];
    }

    /***
     * Nombre del cliente a mostrar en el correo.
     *
     * @return string
     */
    private function resolveClientName(): string
    {
        $client = $this->originalDte->client;

        if (!$client) return "Estimado/a cliente";

        if ($client->corporate_info?->invoice_alias)
            return "Estimado/a" . ucwords($client->corporate_info?->invoice_alias);

        return ucwords("Estimado/a {$client->name} {$client->surname}");
    }

    /***
     * Formatea la fecha para la URL de consulta pública.
     *
     * @param Carbon $date
     * @return string
     */
    private function getDate(Carbon $date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }


    /***
     * Nombre del pdf y del json adjunto.
     *
     * @return string
     */
    private function buildFilename(): string
    {
        return $this->invalidation->generation_code;
    }
}
