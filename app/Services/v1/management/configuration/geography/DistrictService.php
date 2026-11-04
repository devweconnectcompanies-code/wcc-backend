<?php

namespace App\Services\v1\management\configuration\geography;

use App\DTOs\v1\management\configuration\geography\DistrictsDTO;
use App\Models\Configuration\Geography\DistrictModel;

class DistrictService
{
    public function createDistrict(DistrictsDTO $districtData): DistrictModel
    {
        return DistrictModel::create((array)$districtData);
    }

    public function updateDistrict(DistrictModel $districtModel, DistrictsDTO $districtData): DistrictModel
    {
        $districtModel->fill((array)$districtData)->save();
        return $districtModel;
    }
}
