<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Billing\DiscountModel;
use App\Models\Billing\Options\ActivityModel;
use App\Models\Billing\Options\DocumentTypeModel;
use App\Models\Billing\Options\PaymentMethodModel;
use App\Models\Billing\Options\StatusModel;
use Illuminate\Http\JsonResponse;

class BillingController extends Controller
{
    public function billingDocumentsList(): JsonResponse
    {
        return response()->json([
            'response' => DocumentTypeModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function billingActivitiesList(): JsonResponse
    {
        $query = ActivityModel::query()
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json([
            'response' => $query->makeHidden(['status'])
        ]);
    }

    public function statusesList(): JsonResponse
    {
        return response()->json([
            'response' => StatusModel::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get()
                ->makeHidden(['status'])
        ]);
    }

    /***
     * Retorna listado de métodos de pago
     * @return JsonResponse
     */
    public function paymentMethodsList(): JsonResponse
    {
        return response()->json([
            'response' => PaymentMethodModel::query()
                ->select(['id', 'name'])
                ->orderBy('name', 'ASC')
                ->get()
                ->makeHidden(['status'])
        ]);
    }

    /***
     * Retorna listado de descuentos
     * @return JsonResponse
     */
    public function discountList(): JsonResponse
    {
        $query = DiscountModel::query()
            ->select(['id', 'name', 'amount'])
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->get();

        return response()->json([
            'response' => $query->map(function ($el) {
                return [
                    'id' => $el->id,
                    'name' => "{$el->name} ($ {$el->amount})",
                    'amount' => $el->amount,
                ];
            })
        ]);
    }
}
