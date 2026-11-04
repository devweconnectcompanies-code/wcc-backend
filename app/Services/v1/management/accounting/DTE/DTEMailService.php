<?php

namespace App\Services\v1\management\accounting\DTE;

use App\Jobs\accounting\SendDTEMailJob;
use App\Jobs\accounting\SendInvalidationMailJob;
use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class DTEMailService
{
    public function __construct()
    {
    }

    /****
     * Genera el pdf, construye el mailable y lo despacha.
     *
     * @param DTEModel $dteModel
     * @return void
     */
    public function sendDTEMail(DTEModel $dteModel): void
    {
        try {
            $recipientEmail = $this->resolveRecipientEmail($dteModel);

            if (!$recipientEmail) {
                Log::channel('dte_mail')
                    ->warning("[DTE] Sin correo para notificar", ['dte_id' => $dteModel->id]);
                return;
            }

            $jsonContent = $this->encodeJson($dteModel->json_body);

            SendDteMailJob::dispatch(
                dteModel: $dteModel,
                recipientEmail: $recipientEmail,
                jsonContent: $jsonContent,
            );
        } catch (Throwable $e) {
            Log::channel('dte_mail')
                ->error("[DTE] Error al enviar el correo de notificación", [
                    'dte_id' => $dteModel->id,
                    'error' => $e->getMessage(),
                ]);
        }
    }

    /***
     * Envía el correo de notificación cuando ocurre una anulación.
     *
     * @param DTEEventModel $cancelDTEModel
     * @return void
     */
    public function sendInvalidationMail(DTEEventModel $cancelDTEModel): void
    {
        try {
            $originalDte = DTEModel::query()
                ->with('client.email')
                ->findOrFail($cancelDTEModel->dte_id);

            $recipientEmail = $this->resolveRecipientEmail($originalDte);
            $jsonContent = $this->encodeJson($cancelDTEModel->json_body);

            if (!$recipientEmail) {
                Log::channel('dte_mail')
                    ->warning("[DTE] Sin correo para notificar anulación", [
                        'invalidation_id' => $cancelDTEModel->id
                    ]);
                return;
            }

            SendInvalidationMailJob::dispatch(
                cancelDte: $cancelDTEModel,
                dteModel: $originalDte,
                recipientEmail: $recipientEmail,
                jsonContent: $jsonContent,
            );
        } catch (Throwable $e) {
            Log::channel('dte_mail')
                ->error("[DTE] Error al enviar el correo de anulación", [
                    'invalidation_id' => $cancelDTEModel->id,
                    'error' => $e->getMessage(),
                ]);
        }
    }

    /***
     * Resuelve el correo del destinatario según el tipo de cliente.
     *
     * @param DTEModel $dteModel
     * @return string|null
     */
    private function resolveRecipientEmail(DTEModel $dteModel): ?string
    {
        $client = $dteModel->relationLoaded('client')
            ? $dteModel->client
            : $dteModel->client()->with('email')->first();

//        return $client->email?->email;
        return 'sromero.netplus@gmail.com';
    }

    /***
     * Codifica un array a JSON con formato legible.
     *
     * @param array $data
     * @return string
     */
    private function encodeJson(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
