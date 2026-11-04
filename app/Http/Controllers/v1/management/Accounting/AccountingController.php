<?php

namespace App\Http\Controllers\v1\management\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Services\v1\management\accounting\AccountingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AccountingController extends Controller
{
    public function clientInvoices(Request $request, AccountingService $accountingService): JsonResponse
    {
        $year = Carbon::today()->year;
        $invoices = $accountingService->clientInvoices($request->client_id, $year);

        return response()->json([
            'invoices' => new GeneralResource($invoices)
        ]);
    }
}
