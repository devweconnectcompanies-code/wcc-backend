<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Enums\v1\General\InfrastructureStatus;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Infrastructure\EquipmentStatusModel;
use App\Models\Infrastructure\Equipment\BrandModel;
use App\Models\Infrastructure\Equipment\ModelModel;
use App\Models\Infrastructure\Equipment\TypeModel;
use App\Models\Infrastructure\Network\AuthServerModel;
use App\Models\Infrastructure\Network\EquipmentModel;
use App\Models\Infrastructure\Network\NodeModel;
use Illuminate\Http\JsonResponse;

class InfrastructureController extends Controller
{
    public function authServersList(): JsonResponse
    {
        return response()->json([
            'response' => AuthServerModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function equipmentTypesList(): JsonResponse
    {
        return response()->json([
            'response' => TypeModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function equipmentBrandsList(): JsonResponse
    {
        return response()->json([
            'response' => BrandModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function equipmentStatusList(): JsonResponse
    {
        return response()->json([
            'response' => EquipmentStatusModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->orderBy('id', 'ASC')
                ->get()
        ]);
    }

    public function nodesList(): JsonResponse
    {
        return response()->json([
            'response' => NodeModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function modelsList(): JsonResponse
    {
        return response()->json([
            'response' => ModelModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function modelsByBrand(int $brandID): JsonResponse
    {
        return response()->json([
            'response' => ModelModel::query()
                ->where('brand_id', $brandID)
                ->select(['id', 'name'])
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function equipmentByNode(int $id): JsonResponse
    {
        $query = EquipmentModel::query()
            ->where([
                ['node_id', $id],
                ['status_id', InfrastructureStatus::OPERATIVE->value]
            ])
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json(['response' => $query]);
    }
}
