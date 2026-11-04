<?php

namespace App\Jobs\clients;

use App\Enums\v1\General\CommonStatus;
use App\Models\Clients\ContractModel;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotifyExpiringContractsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Cantidad de intentos si el job falla.
     * @var int
     */
    public int $tries = 3;

    /**
     * Tiempo entre reintentos.
     * @var int
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $days = 15)
    {
    }

    /**
     * Evita que se acumulen jobs si el anterior sigue corriendo.
     * @return array
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping('background:notify-expiring-contracts')->releaseAfter(30)];
    }

    /**
     * Execute the job.
     * @return void
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle(): void
    {
        $today = Carbon::today();
        $limit = $today->copy()->addDays($this->days);

        $contracts = ContractModel::query()
            ->with('client.mobile')
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereDate('contract_end_date', '>=', $today)
            ->whereDate('contract_end_date', '<=', $limit)
            ->orderBy('contract_end_date')
            ->get();

        if ($contracts->isEmpty()) {
            Log::channel('expiring_contracts')
                ->info('No hay contratos próximos a vencer.');

            return;
        }

        /**
         * Dividiendo los contratos en grupos de 20
         * para evitar mensajes demasiado grandes.
         */

        $chunks = $contracts->chunk(20);

        foreach ($chunks as $index => $chunk) {
            $message = $this->buildMessage(
                contracts: $chunk,
                days: $this->days,
                withHeader: $index === 0
            );
            $this->sendTelegramMessage($message);
        }
        Log::channel('expiring_contracts')
            ->info("Se notificaron {$contracts->count()} contrato(s) próximos a vencer.");
    }

    /**
     * Construye el mensaje que será enviado a telegram.
     *
     * @param Collection $contracts
     * @param int $days
     * @param bool $withHeader
     * @return string
     */
    protected function buildMessage(Collection $contracts, int $days, bool $withHeader): string
    {
        $lines = [];

        if ($withHeader) {
            $lines[] = '📋 <b>Contratos próximos a vencer</b>';
            $lines[] = "⏳ Próximos {$days} día(s)";
            $lines[] = '================================';
            $lines[] = '';
        }

        foreach ($contracts as $contract) {
            $client = $contract->client;
            $clientName = $client
                ? trim("{$client->name} {$client->surname}")
                : "Cliente #{$contract->client_id} no encontrado.";
            $phone = $client ? trim("{$client->mobile?->number}") : "Número de teléfono no disponible.";
            $endDate = Carbon::parse($contract->contract_end_date)->format('Y/m/d');
            $diff = $contract->diff_days;

            //  Nivel de urgencia
            if ($diff <= 2) {
                $statusEmoji = '🔴';
                $statusText = $diff === 0 ? 'Hoy' : "Faltan {$diff} día(s)";
            } elseif ($diff <= 7) {
                $statusEmoji = '🟠';
                $statusText = "Faltan {$diff} días";
            } else {
                $statusEmoji = '🟢';
                $statusText = "Faltan {$diff} días";
            }

            $lines[] = '👤 <b>' . e($clientName) . '</b>';
            $lines[] = '📅 Vence: ' . e("{$endDate}");
            $lines[] = '📱 Teléfono: ' . e("{$phone}");
            $lines[] = "{$statusEmoji} {$statusText}";
            $lines[] = "";
        }

        $lines[] = '================================';
        $lines[] = '📊 <b>Total:</b> ' . $contracts->count() . ' contrato(s)';

        return implode("\n", $lines);
    }

    /**
     * Usa API de Telegram para enviar el mensaje.
     * @param string $message
     * @return void
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function sendTelegramMessage(string $message): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (!$token || !$chatId) {
            Log::channel('expiring_contracts')
                ->warning('Telegram bot token o chat id no configurados. No se pudo enviar la notificación de contratos.');
            return;
        }

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

        if ($response->failed()) {
            Log::channel('expiring_contracts')
                ->error("Error al enviar mensaje a Telegram (contratos por vencer)", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            $response->throw();
        }

        Log::channel('expiring_contracts')
            ->info('Notificación de contratos próximos a vencer enviada correctamente.');
    }

    public function failed(?Throwable $e): void
    {
        Log::channel('expiring_contracts')
            ->error("El Job de contratos próximos a vencer falló.", [
                'days' => $this->days,
                'error' => $e?->getMessage(),
            ]);
    }
}
