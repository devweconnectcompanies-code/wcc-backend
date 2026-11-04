<?php

namespace App\Services\v1\management\accounting\DTE;

use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTELogModel;
use App\Models\Accounting\DTEModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class DTELogService
{
    /**
     * Registra la respuesta de hacienda usando el código de generación como identificador.
     *
     * @param string $generationCode
     * @param array|object $haciendaResponse
     * @return DTELogModel|null
     */
    public function logResponse(string $generationCode, array|object $haciendaResponse): ?DTELogModel
    {
        try {
            return DTELogModel::query()
                ->create([
                    'generation_code' => $generationCode,
                    'json_response' => $haciendaResponse,
                    'transaction_date' => Carbon::now(),
                ]);
        } catch (Throwable $e) {
            Log::channel('dte_logger')->error("[DTE] Error al almacenar la respuesta de hacienda", [
                'generation_code' => $generationCode,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Asocia post-hoc el log ya creado con el DTE o evento almacenado.
     *
     * @param string $generationCode
     * @param DTEModel|DTEEventModel $model
     * @return void
     */
    public function linkToModel(string $generationCode, DTEModel|DTEEventModel $model): void
    {
        try {
            $isEvent = $model instanceof DTEEventModel;

            DTELogModel::query()
                ->where('generation_code', $generationCode)
                ->whereNull($isEvent ? 'event_id' : 'dte_id')
                ->update([
                    $isEvent ? 'event_id' : 'dte_id' => $model->id,
                ]);
        } catch (Throwable $e) {
            Log::channel('dte_logger')->error("[DTE] Error al vincular el log con el modelo", [
                'generation_code' => $generationCode,
                'model_id' => $model->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Almacena el registro de la transacción haya sido aprobado el DTE o no.
     *
     * @param string $generationCode
     * @param array|object $haciendaResponse
     * @param int|null $dteId
     * @param int|null $eventId
     * @param int|null $clientId
     * @param array|null $json
     * @return DTELogModel|null
     */
    public function logHaciendaResponse(
        string       $generationCode,
        array|object $haciendaResponse,
        ?int         $dteId = null,
        ?int         $eventId = null,
        ?int         $clientId = null,
        ?array       $json = null
    ): ?DTELogModel
    {
        try {
            return DTELogModel::query()
                ->create([
                    'dte_id' => $dteId,
                    'event_id' => $eventId,
                    'json_response' => $haciendaResponse,
                    'transaction_date' => Carbon::now(),
                    'client_id' => $clientId,
                    'generation_code' => $generationCode,
                    'json_content' => $json,
                ]);
        } catch (Throwable $e) {
            Log::channel('dte_logger')->error("[DTE] Error al almacenar la transacción", [
                'generation_code' => $generationCode,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
