<?php

namespace App\Http\Controllers\v1\management\billing;

use App\DTOs\v1\management\billing\prepayment\PrepaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\Prepayments\PrepaymentRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Billing\PrepaymentModel;
use App\Services\v1\management\billing\PrepaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrepaymentController extends Controller
{
    /***
     * Registra abono
     * @param PrepaymentRequest $request
     * @param PrepaymentService $service
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(PrepaymentRequest $request, PrepaymentService $service): JsonResponse
    {
        $dto = new PrepaymentDTO(
            client_id: $request->client,
            amount: $request->amount,
            payment_method_id: $request->payment_method,
            payment_date: Carbon::now(),
            user_id: Auth::user()->id,
            reference_number: $request->reference,
            comments: $request->comments,
            status_id: $request->status,
        );
        $prepayment = $service->createPrepayment($dto);

        return response()->json([
            'saved' => (bool)$prepayment,
            'prepayment' => new GeneralResource($prepayment),
        ]);
    }

    /***
     * Obtiene los datos de un abono.
     * @param Request $request
     * @param PrepaymentService $service
     * @return JsonResponse
     */
    public function edit(Request $request, PrepaymentService $service): JsonResponse
    {
        return response()->json([
            'prepayment' => new GeneralResource($service->prepaymentInfo($request->prepayment)),
        ]);
    }

    /***
     * Actualiza información de un abono.
     * @param PrepaymentRequest $request
     * @param PrepaymentModel $id
     * @param PrepaymentService $service
     * @return JsonResponse
     */
    public function update(PrepaymentRequest $request, PrepaymentModel $id, PrepaymentService $service): JsonResponse
    {
        try {
            $data = [
                'amount' => $request->amount,
                'payment_method_id' => $request->payment_method,
                'status_id' => $request->status,
                'comments' => $request->comments,
            ];
            $prepayment = $service->updatePrepayment($id, $data);

            return response()->json([
                'saved' => (bool)$prepayment,
                'prepayment' => new GeneralResource($prepayment),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'saved' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /***
     * Obtiene listado de abonos de un cliente
     * @param Request $request
     * @param PrepaymentService $service
     * @return JsonResponse
     */
    public function listByClient(Request $request, PrepaymentService $service): JsonResponse
    {
        return response()->json([
            'list' => new GeneralResource($service->listByClient($request->client)),
        ]);
    }
}
