<?php

namespace App\Http\Controllers\v1\management\Accounting\DTE;

use App\Http\Controllers\Controller;
use App\Models\Accounting\DTEModel;
use App\Services\v1\management\accounting\DTE\DTEOrchestrator;
use App\Services\v1\management\accounting\DTE\DTEPrintService;
use App\Services\v1\management\accounting\DTE\DTEService;
use App\Services\v1\management\accounting\DTE\events\RefundOrchestratorService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DTEController extends Controller
{
    public function __construct(
        private readonly DTEOrchestrator $dteOrchestrator,
        private readonly DTEPrintService $dtePrintService,
    )
    {

    }

    /***
     * Data Grid con los DTE emitidos.
     *
     * @param Request $request
     * @param DataViewerService $dataViewerService
     * @return JsonResponse
     */
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = DTEModel::query()
            ->with([
                'client',
                'dte_type',
                'user',
                'invalidation',
                'status',
            ]);

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'type' => fn($q, $data) => $q->whereIn('document_type_id', $data),
            'user' => fn($q, $data) => $q->whereIn('user_id', $data),
        ]);
    }

    /***
     * Emite el documento tributario electrónico
     *
     * @param Request $request
     * @param int $documentId
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request, int $documentId): JsonResponse
    {
        return response()->json(
            $this->dteOrchestrator->process($documentId, $request->all())
        );
    }

    /***
     * Imprime el DTE según ID
     *
     * @param int $dteId
     * @return Response
     */
    public function printDTE(int $dteId): Response
    {
        $pdf = $this->dtePrintService->print($dteId);

        return $pdf->stream();
    }

    /***
     * Busca DTE con base en número de control.
     *
     * @param Request $request
     * @param DTEService $service
     * @return JsonResponse
     */
    public function search(Request $request, DTEService $service): JsonResponse
    {
        return response()->json([
            'documents' => $service->search($request->control_number)
        ]);
    }

    /**
     * Reenvía el correo con los datos del DTE emitido.
     *
     * @param Request $request
     * @param DTEService $service
     * @return JsonResponse
     */
    public function resendMail(Request $request, DTEService $service): JsonResponse
    {
        $dte = DTEModel::query()->findOrFail($request->dte_id);
        $service->resendMail($dte);

        return response()->json([
            'send' => true,
        ]);
    }

    /****
     * Realiza las devoluciones en facturas.
     *
     * @param Request $request
     * @param RefundOrchestratorService $refundOrchestratorService
     * @return JsonResponse
     */
    public function refund(Request $request, RefundOrchestratorService $refundOrchestratorService): JsonResponse
    {
        return response()->json([
            'refund' => $refundOrchestratorService->process(
                dteId: $request->dte_id,
                dteType: $request->dte_type,
                items: $request->items,
            ),
        ]);
    }
}
