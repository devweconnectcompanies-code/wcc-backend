<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Infrastructure\Network\NodeModel;
use App\Models\Supports\StatusModel;
use App\Models\Supports\TypeModel as SupportType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportsController extends Controller
{
    public function supports_status(): JsonResponse
    {
        return response()->json([
            'response' => StatusModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('id', 'ASC')
                ->get()
                ->makeHidden('status'),
        ]);
    }

    public function supports_types(): JsonResponse
    {
        return response()->json([
            'response' => SupportType::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
                ->makeHidden('status'),
        ]);
    }
}
