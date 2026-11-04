<?php

namespace App\Services\v1\management\accounting\DTE;

use App\Enums\v1\Accounting\DTE\EventTypes;
use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

readonly class DTEStorageService
{
    /****
     * Almacena el JSON del DTE como archivo .json en S3.
     *
     * @param DTEModel $dteModel
     * @return void
     */
    public function storeDTEJson(DTEModel $dteModel): void
    {
        try {
            $year = $dteModel->generation_datetime->year;
            $type = DocumentTypes::from($dteModel->document_type_id);
            $filename = $this->safeFileName($dteModel->control_number) . '.json';
            $path = "dte/json/{$year}/{$type->folderName()}/{$filename}";
            $content = $this->encodeJson($dteModel->json_body);

            if (!Storage::disk('s3')->put($path, $content)) {
                Log::channel('dte_storage')
                    ->error("[DTE] Error al guardar el archivo", ['path' => $path]);
            }
        } catch (Throwable $e) {
            Log::channel('dte_storage')
                ->error("[DTE] Error al almacenar el JSON en S3", [
                    'dte_id' => $dteModel->id,
                    'error' => $e->getMessage(),
                ]);
        }
    }

    /****
     * Almacena el JSON de cualquier evento en S3.
     *
     * @param DTEEventModel $eventModel
     * @return void
     */
    public function storeEventJson(DTEEventModel $eventModel): void
    {
        try {
            $dte = $eventModel->dte;
            $eventType = EventTypes::from($eventModel->event_type_id);
            $type = DocumentTypes::from($dte->document_type_id);
            $year = $eventModel->generation_datetime->year;
            $filename = $this->safeFileName($eventModel->generation_code) . '.json';
            $path = "dte/json/{$year}/{$eventType->folderName()}/{$type->folderName()}/{$filename}";
            $content = $this->encodeJson($eventModel->json_body);

            if (!Storage::disk('s3')->put($path, $content)) {
                Log::channel('dte_storage')
                    ->error("[DTE] Error al guardar el archivo de anulación", ['path' => $path]);
            }
        } catch (Throwable $e) {
            Log::channel('dte_storage')
                ->error("[DTE] Error al almacenar el JSON de anulación en S3", [
                    'invalidation_id' => $eventModel->id,
                    'error' => $e->getMessage(),
                ]);
        }
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

    /***
     * Sanitiza una cadena para usarla como nombre de archivo seguro.
     *
     * @param string $val
     * @return string
     */
    private function safeFileName(string $val): string
    {
        return str_replace(['/', '\\', ' ', ':'], '-', $val);
    }
}
