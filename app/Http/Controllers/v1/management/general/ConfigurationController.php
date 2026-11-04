<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Configuration\MenuModel;
use Illuminate\Http\JsonResponse;

class ConfigurationController extends Controller
{
    public function menuList(): JsonResponse
    {
        return response()->json([
            'response' => MenuModel::query()
                ->select('id', 'slug as name')
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }
}
