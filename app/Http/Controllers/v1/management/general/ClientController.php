<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Clients\ClientTypeModel;
use App\Models\Configuration\Clients\DocumentTypeModel;
use App\Models\Configuration\Clients\KinshipModel;
use App\Models\Configuration\Clients\PhoneTypeModel;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function clientTypesList(): JsonResponse
    {
        return response()->json([
            'response' => ClientTypeModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function personalDocumentsList(): JsonResponse
    {
        return response()->json([
            'response' => DocumentTypeModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name', 'status_id')
                ->get()
        ]);
    }

    public function phoneCategoriesList(): JsonResponse
    {
        return response()->json([
            'response' => PhoneTypeModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select(['id', 'name'])
                ->get()
        ]);
    }

    public function referencesList(): JsonResponse
    {
        return response()->json([
            'response' => KinshipModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }
}
