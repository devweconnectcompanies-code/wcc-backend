<?php

namespace App\Http\Controllers\v1\management\billing;

use App\DTOs\v1\management\billing\payments\PaymentDTO;
use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Billing\Payments\PaymentRequest;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Services\v1\management\billing\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /***
     * Registra pago realizado
     * @param PaymentRequest $request
     * @param PaymentService $service
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(PaymentRequest $request, PaymentService $service): JsonResponse
    {
        $ids = array_map('intval', explode(',', $request->invoices));
        $dto = new PaymentDTO(
            client_id: $request->client,
            payment_method_id: $request->payment_method,
            amount: $request->amount,
            payment_date: Carbon::today(),
            reference_number: null,
            user_id: Auth::user()->id,
            comments: $request->comments,
            status_id: CommonStatus::ACTIVE->value,
        );

        $payment = $service->createPayment($dto, $ids, $request->discount);

        return response()->json([
            'saved' => (bool)$payment,
            'payment' => new GeneralResource($payment),
        ]);
    }

    /***
     * Retorna el listado de pagos realizados por un cliente.
     * @param Request $request
     * @param PaymentService $service
     * @return JsonResponse
     */
    public function paymentList(Request $request, PaymentService $service): JsonResponse
    {
        $list = $service->clientPayments($request->client);

        return response()->json([
            'list' => new GeneralResource($list)
        ]);
    }
}
