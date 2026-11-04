<?php

namespace App\Http\Controllers\v1\management\general;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Config\StatusModel;
use Illuminate\Http\JsonResponse;

class DTEController extends Controller
{
    public function status(): JsonResponse
    {
        $query = StatusModel::query()
            ->where('status', true)
            ->get();

        return response()->json(['response' => $query]);
    }
}
