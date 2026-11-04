<?php

namespace App\Services\v1\management\accounting\DTE;

use App\DTOs\v1\management\accounting\dte\DTEEventsDTO;
use App\DTOs\v1\management\accounting\dte\DTEDTO;
use App\Enums\v1\Accounting\DTE\EventTypes;
use App\Enums\v1\Accounting\DTE\Status;
use App\Enums\v1\Accounting\InvoiceCategories;
use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Accounting\DTEEventModel;
use App\Models\Accounting\DTEModel;
use App\Models\Billing\PaymentModel;
use App\Services\v1\management\billing\otherInvoices\OtherInvoiceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

readonly class DTEOrchestrator
{
    public function __construct(
        private DTEService          $dteService,
        private DTESignatureService $dteSignatureService,
        private DTEStorageService   $dteStorageService,
        private DTEMailService      $dteMailService,
        private OtherInvoiceService $otherInvoiceService,
        private DTELogService       $dteLogService,
    )
    {
    }

    /***
     * Punto de entrada principal. Enruta al escenario correspondiente según el source.
     *
     * @param int $documentId
     * @param array $data
     * @return DTEModel|DTEEventModel
     * @throws Throwable
     */
    public function process(int $documentId, array $data): DTEModel|DTEEventModel
    {
        $source = $data['source'] ?? 'payment';

        if ($source === 'invalidation') return $this->processInvalidation($data);

        return $this->processDTE(documentId: $documentId, data: $data, source: $source);
    }

    /****
     * Genera json, almacena DTE y envía correo de notificación.
     *
     * @param int $documentId
     * @param array $data
     * @param string $source
     * @return DTEModel
     * @throws Throwable|RuntimeException
     */
    private function processDTE(int $documentId, array $data, string $source): DTEModel
    {
        $json = $this->dteService->generate(documentId: $documentId, data: $data);
        $userId = Auth::id() ?? throw new  RuntimeException("Usuario no autenticado");
        [$clientId, $paymentId, $invoiceIds, $otherInvoiceId, $category] = $this->resolveSourceContext(
            source: $source,
            data: $data,
            userId: $userId,
            documentId: $documentId,
        );
        $signDocument = $this->dteSignatureService->signDocument($json);
//        $result = $this->dteSignatureService->singAndSend(dte: $json, documentId: $documentId);
//        $receptionStamp = $result->haciendaResponse->selloRecibido
//            ?? throw new RuntimeException("Hacienda no devolvió sello de recepción");
        $genCode = $json['identificacion']['codigoGeneracion'];
        $receptionStamp = strtoupper(Str::random(40));

        $this->dteLogService->logHaciendaResponse(
            generationCode: $genCode,
            haciendaResponse: /*$result->haciendaResponse*/ [['saludo' => 'hola'], ['usuario' => 'Sear']],
            clientId: $clientId,
            json: $json,
        );

        $json['firmaElectronica'] = $signDocument->body;
//        $json['firmaElectronica'] = $result->signedDocument;
        $json['selloRecibido'] = $receptionStamp;

        $type = DocumentTypes::from($documentId);

        $dto = new DTEDTO(
            client_id: (int)$clientId,
            document_type_id: $documentId,
            control_number: $json['identificacion']['numeroControl'],
            generation_code: $genCode,
            reception_stamp: $receptionStamp,
            generation_datetime: Carbon::now(),
            total_amount: (float)$json['resumen'][$type->totalAmountKey()],
            payment_id: $paymentId,
            invoice_category: $category,
            invoice_ids: $invoiceIds,
            other_invoice_id: $otherInvoiceId,
            user_id: $userId,
            status_id: Status::EMITIDO->value,
            json_body: $json,
        );

        $dte = $this->dteService->storeDTE($dto);
        $this->dteLogService->linkToModel(generationCode: $genCode, model: $dte);
        $this->dteStorageService->storeDTEJson($dte);
        $this->dteMailService->sendDTEMail($dte);

        return $dte;
    }

    /***
     * Genera el json para anulación, lo almacena, y notifica mediante email
     *
     * @param array $data
     * @return DTEEventModel
     * @throws Throwable|RuntimeException
     */
    private function processInvalidation(array $data): DTEEventModel
    {
        $userId = Auth::id() ?? throw new RuntimeException("Usuario no autenticado");
        $originalDTE = DTEModel::query()
            ->select(['id', 'client_id'])
            ->findOrFail((int)$data['dte_id']);

        $json = $this->dteService->generate(documentId: DocumentTypes::ANULACION->value, data: $data);
        $genCode = $json['identificacion']['codigoGeneracion'];
        $signDocument = $this->dteSignatureService->signDocument($json);
//        $result = $this->dteSignatureService->singAndSend(
//            dte: $json,
//            documentId: DocumentTypes::ANULACION->value
//        );
//        $receptionStamp = $result->haciendaResponse->selloRecibido
//            ?? throw new  RuntimeException("Hacienda no retorno el sello de recepción");
        $this->dteLogService->logHaciendaResponse(
            generationCode: $genCode,
            haciendaResponse: [['saludo' => 'hola'], ['usuario' => 'Sear']],
            clientId: $originalDTE->client_id,
            json: $json,
        );
        $receptionStamp = strtoupper(Str::random(40));
        $json['firmaElectronica'] = $signDocument->body;
//        $json['firmaElectronica'] = $result->signedDocument;
        $json['selloRecibido'] = $receptionStamp;

//        $haciendaResponse = $this->dteSignatureService->singAndSend(
//            dte: $json,
//            documentId: DocumentTypes::ANULACION->value
//        );
//        $receptionStamp = $haciendaResponse->selloRecibido
//            ?? throw new RuntimeException("Hacienda no devolvió sello de recepción");

        $dto = new DTEEventsDTO(
            dte_id: (int)$data['dte_id'],
            generation_code: $json['identificacion']['codigoGeneracion'],
            reception_stamp: $receptionStamp,
            generation_datetime: Carbon::now(),
            user_id: $userId,
            json_body: $json,
            status_id: true,
            event_type_id: EventTypes::INVALIDACION->value,
        );

        $invalidation = $this->dteService->storeEvent($dto);

        DTEModel::query()
            ->where('id', (int)$data['dte_id'])
            ->update(['status_id' => Status::ANULADO->value]);

        $this->dteLogService->linkToModel(generationCode: $genCode, model: $invalidation);
        $this->dteStorageService->storeEventJson($invalidation);
        $this->dteMailService->sendInvalidationMail($invalidation);

        return $invalidation;
    }

    /****
     * Resuelve el contexto de datos según el origen del documento.
     *
     * @param string $source
     * @param array $data
     * @param int $userId
     * @param int $documentId
     * @return array
     * @throws Throwable
     */
    private function resolveSourceContext(string $source, array $data, int $userId, int $documentId): array
    {
        $clientId = $data['client_id'] ?? null;
        $paymentId = null;
        $invoiceIds = null;
        $otherInvoiceId = null;
        $category = InvoiceCategories::INVOICE;

        switch ($source) {
            //  Escenario 1: Pago registrado
            case 'payment':
                $payment = PaymentModel::query()
                    ->with('invoices')
                    ->findOrFail($data['payment']);
                $clientId = $payment->client_id;
                $paymentId = $payment->id;
                $invoiceIds = $payment->invoices->pluck('id')->toArray();
                break;

            //  Escenario 2: Selección de facturas
            case 'invoices':
                $invoiceIds = $data['items'];
                break;

            //  Escenario 3: Llenado de formulario
            case 'manual':
                $otherInvoice = $this->otherInvoiceService->createFromManualData(
                    type: $documentId,
                    data: $data,
                    userId: $userId,
                );
                $otherInvoiceId = $otherInvoice->id;
                $category = InvoiceCategories::OTHER_INVOICE;
                break;

            default:
                throw new \InvalidArgumentException("Origen no soportado: {$source}");
        }

        return [$clientId, $paymentId, $invoiceIds, $otherInvoiceId, $category];
    }
}
