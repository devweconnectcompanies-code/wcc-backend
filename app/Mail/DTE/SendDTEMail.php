<?php

namespace App\Mail\DTE;

use App\Models\Accounting\DTEModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendDTEMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly DTEModel $dteModel,
        public readonly string   $pdfOutput,
        public readonly string   $jsonContent
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
            subject: "Documento Tributario Electrónico - {$this->dteModel->generation_code}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'v1.management.mails.dte.dte_mail',
            with: [
                'dte' => $this->dteModel,
                'clientName' => $this->resolveClientName(),
                'dteTypeName' => $this->dteModel->dte_type?->name ?? 'Documento Tributario Electrónico.',
                'generatedAt' => Carbon::parse($this->dteModel->generation_datetime)
                    ->locale('es')
                    ->isoFormat('dddd D [de] MMMM [del] YYYY [a las] HH:mm'),
                'uri' => "https://admin.factura.gob.sv/consultaPublica?ambiente=01&codGen={$this->dteModel->generation_code}&fechaEmi={$this->getDate($this->dteModel->generation_datetime)}"
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
        $filename = $this->buildFileName();
        return [
            Attachment::fromData(
                fn() => $this->pdfOutput,
                "{$filename}.pdf"
            )->withMime('application/pdf'),

            Attachment::fromData(
                fn() => $this->jsonContent,
                "{$filename}.json"
            )->withMime('application/json'),
        ];
    }

    /***
     * Nombre del cliente a mostrar en el correo.
     *
     * @return string
     */
    private function resolveClientName(): string
    {
        $client = $this->dteModel->client;

        if (!$client) {
            return 'Estimado/a cliente';
        }

        if ($client->corporate_info?->invoice_alias) {
            return 'Estimado/a ' . ucwords($client->corporate_info?->invoice_alias);
        }

        return ucwords("Estimado/a {$client->name} {$client->surname}");
    }

    /***
     * Nombre tanto del pdf como del json adjuntos.
     *
     * @return string
     */
    private function buildFileName(): string
    {
//        $controlSafe = str_replace(['/', '\\', ' '], '-', $this->dteModel->generation_code);
        return $this->dteModel->generation_code;
    }

    /***
     * Retorna la fecha de generación del DTE en formato Y-m-d.
     *
     * @param Carbon $date
     * @return string
     */
    private function getDate(Carbon $date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}
