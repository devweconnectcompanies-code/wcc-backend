<?php

namespace App\Http\Controllers\v1\management\imports;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\general\GeneralResource;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Services\v1\imports\ImportMikrotikPPPService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /***
     * @param ImportMikrotikPPPService $service
     * @return JsonResponse
     */
    public function createSecrets(ImportMikrotikPPPService $service): JsonResponse
    {
        $server = AuthServerModel::query()->findOrFail(1);
        $import = $service->sync($server->toArray());

        return response()->json([
            'data' => new GeneralResource($import),
        ]);
    }
}
