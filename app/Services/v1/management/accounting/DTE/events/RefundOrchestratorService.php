<?php

namespace App\Services\v1\management\accounting\DTE\events;

use App\DTOs\v1\management\accounting\dte\DTEEventsDTO;
use App\Enums\v1\Accounting\DTE\EventTypes;
use App\Enums\v1\Accounting\DTE\Status;
use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use App\Services\v1\management\accounting\DTE\DTELogService;
use App\Services\v1\management\accounting\DTE\DTEService;
use App\Services\v1\management\accounting\DTE\DTESignatureService;
use App\Services\v1\management\accounting\DTE\DTEStorageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

readonly class RefundOrchestratorService
{
    public function __construct(
        private readonly RefundStructureService $refundStructureService,
        private readonly DTESignatureService    $dteSignatureService,
        private readonly DTEService             $dteService,
        private readonly DTEStorageService      $dteStorageService,
        private readonly DTELogService          $dteLogService,
    )
    {

    }

    /**
     * Procesa el envío del evento reembolso.
     *
     * @param int $dteId
     * @param int $dteType
     * @param array $items
     * @return DTEEventModel
     * @throws \Random\RandomException
     */
    public function process(int $dteId, int $dteType, array $items): DTEEventModel
    {
        $user = Auth::id() ?? throw new \RuntimeException("Usuario no autenticado.");
        $relatedDTE = DTEModel::query()
            ->select(['id', 'client_id'])
            ->findOrFail($dteId);

        // 1. Crear el JSON
        $json = $this->refundStructureService->createJson(
            dteId: $dteId,
            dteType: $dteType,
            items: $items
        );
        $genCode = $json['identificacion']['codigoGeneracion'];
        $result = $this->dteSignatureService->signDocument($json);
        $receptionStamp = strtoupper(Str::random(40));
//        $result = $this->dteSignatureService->singAndSendEvent(dte: $json, eventType: EventTypes::RETORNO);
//        $receptionStamp = $result->haciendaResponse->selloRecibido
//            ?? throw new \RuntimeException("Hacienda no retorno el sello de recepción");
//        $json['firmaElectronica'] = $result->signedDocument;
        $this->dteLogService->logHaciendaResponse(
            generationCode: $genCode,
            haciendaResponse: /*$result->haciendaResponse*/ [['saludo' => 'Hola'], ['usuario' => 'Sear']],
            clientId: $relatedDTE->client_id,
            json: $json,
        );
        $json['firmaElectronica'] = $result->body;
        $json['selloRecibido'] = $receptionStamp;

        $dto = new DTEEventsDTO(
            dte_id: (int)$dteId,
            generation_code: $genCode,
            reception_stamp: $receptionStamp,
            generation_datetime: Carbon::now(),
            user_id: $user,
            json_body: $json,
            status_id: true,
            event_type_id: EventTypes::RETORNO->value,
        );

        $refundTransaction = $this->dteService->storeEvent($dto);

        DTEModel::query()
            ->where('id', (int)$dteId)
            ->update(['status_id' => Status::CON_DEVOLUCION->value]);

        $this->dteLogService->linkToModel(generationCode: $genCode, model: $refundTransaction);
        $this->dteStorageService->storeEventJson($refundTransaction);

        return $refundTransaction;
    }
}
