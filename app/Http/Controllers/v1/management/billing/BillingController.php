<?php

namespace App\Http\Controllers\v1\management\billing;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Jobs\billing\RecalculateInvoiceById;
use App\Models\Billing\InvoiceModel;
use App\Models\Clients\ClientModel;
use App\Services\v1\management\billing\invoices\InvoiceUpdater;
use App\Services\v1\management\billing\InvoicesService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingController extends Controller
{
    /***
     * @param Request $request
     * @param DataViewerService $dataViewerService
     * @return JsonResponse
     */
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = ClientModel::query()
            ->with([
                'branch:id,name,badge_color',
                'client_type:id,name',
                'dui',
                'mobile',
                'address.state',
                'address.district',
                'financial_status.status',
            ]);

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'branch' => fn($q, $data) => $q->whereIn('branch_id', $data),
            'type' => fn($q, $data) => $q->whereIn('client_type_id', $data),
            'state' => fn($q, $data) => $q->whereHas('address', function ($q) use ($data) {
                return $q->whereIn('state_id', $data);
            }),
            'district' => fn($q, $data) => $q->whereHas('address', function ($q) use ($data) {
                return $q->whereIn('district_id', $data);
            }),
            'financial_status' => fn($q, $data) => $q->whereHas('financial_status', function ($q) use ($data) {
                return $q->whereIn('status_id', $data);
            }),
        ]);
    }

    /***
     * @param Request $request
     * @param InvoicesService $service
     * @return JsonResponse
     */
    public function clientInvoices(Request $request, InvoicesService $service): JsonResponse
    {
        $invoices = $service->getClientInvoices($request->input('client_id'));

        return response()->json([
            'invoices' => new GeneralResource($invoices),
        ]);
    }

    /***
     *  Retorna el pdf con los datos de la factura
     * @param int $invoiceId
     * @param InvoicesService $service
     * @return Response
     */
    public function printInvoice(int $invoiceId, InvoicesService $service): Response
    {
        $pdf = $service->getInvoiceData($invoiceId);
        return $pdf->stream();
    }

    /***
     * Retorna la fecha de corte del período de una factura
     * @param int $id
     * @param InvoicesService $service
     * @return JsonResponse
     */
    public function invoiceDueDate(int $id, InvoicesService $service): JsonResponse
    {
        $date = $service->getInvoiceDueDate($id);

        return response()->json([
            'due_date' => $date,
        ]);
    }

    /***
     * Retorna las facturas pendientes o vencidas de un cliente
     * @param int $clientId
     * @param InvoicesService $service
     * @return JsonResponse
     */
    public function getClientPendingInvoices(int $clientId, InvoicesService $service): JsonResponse
    {
        return response()->json([
            'response' => new GeneralResource($service->clientPendingInvoices($clientId))
        ]);
    }

    /**
     * Recalcula el valor de una factura según su id.
     *
     * @param Request $request
     * @param InvoiceUpdater $updater
     * @return JsonResponse
     * @throws \Throwable
     */
    public function recalculateInvoice(Request $request, InvoiceUpdater $updater): JsonResponse
    {
//        RecalculateInvoiceById::dispatch($request->invoice_id);
        $invoice = $updater->recalculateInvoiceById($request->invoice_id);

        return response()->json([
            'success' => (bool)$invoice,
            'data' => new GeneralResource($invoice)
        ]);
    }

}
