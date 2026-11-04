<?php

namespace App\Http\Controllers\v1\management\general;

use App\Enums\v1\General\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Clients\ContractStateModel;
use App\Models\Configuration\BranchModel;
use App\Models\Configuration\Clients\GenderModel;
use App\Models\Configuration\Clients\MaritalStatusModel;
use App\Models\Configuration\Geography\CountryModel;
use App\Models\Configuration\Geography\DistrictModel;
use App\Models\Configuration\Geography\MunicipalityModel;
use App\Models\Configuration\Geography\StateModel;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{
    public function generalStatus(): JsonResponse
    {
        $status = [
            [
                'id' => CommonStatus::INACTIVE->value,
                'name' => 'Inactivo'
            ],
            [
                'id' => CommonStatus::ACTIVE->value,
                'name' => 'Activo'
            ],
        ];

        return response()->json(['response' => $status]);
    }

    public function statesList(): JsonResponse
    {
        return response()->json([
            'response' => StateModel::query()
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function municipalitiesList(): JsonResponse
    {
        return response()->json([
            'response' => MunicipalityModel::query()
                ->select('id', 'name')
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function municipalitiesByState(int $stateID): JsonResponse
    {
        return response()->json([
            'response' => MunicipalityModel::query()
                ->select('id', 'name')
                ->where('state_id', $stateID)
                ->orderBy('name', 'ASC')
                ->get()
        ]);
    }

    public function districtsList(): JsonResponse
    {
        return response()->json(['response' => DistrictModel::query()->select('id', 'name')->get()]);
    }

    public function countriesList(): JsonResponse
    {
        $query = CountryModel::query()->select('id', 'es_name')->get();
        $result = $query->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->es_name,
            ];
        });

        return response()->json(['response' => $result]);
    }

    public function countriesWithCode(): JsonResponse
    {
        $query = CountryModel::query()->select('iso_2', 'es_name')->get();
        $result = $query->map(function ($item) {
            return [
                'id' => $item->iso_2,
                'name' => $item->es_name,
            ];
        });

        return response()->json(['response' => $result]);
    }

    public function districtsByMunicipality(int $municipalityID): JsonResponse
    {
        $query = DistrictModel::query()
            ->select('id', 'name')
            ->where('municipality_id', $municipalityID)
            ->get();

        return response()->json(['response' => $query]);
    }

    public function gendersList(): JsonResponse
    {
        return response()->json([
            'response' => GenderModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function maritalStatusList(): JsonResponse
    {
        return response()->json([
            'response' => MaritalStatusModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function branchesList(): JsonResponse
    {
        return response()->json([
            'response' => BranchModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }

    public function contractStatusList(): JsonResponse
    {
        return response()->json([
            'response' => ContractStateModel::query()
                ->where('status_id', CommonStatus::ACTIVE->value)
                ->select('id', 'name')
                ->get()
        ]);
    }
}
