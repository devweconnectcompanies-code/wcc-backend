<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\GenderDTO;
use App\Models\Configuration\Clients\GenderModel;

class GenderService
{
    public function createGender(GenderDTO $genderData): GenderModel
    {
//        return GenderModel::create((array)$genderData);
        return GenderModel::create($genderData->toArray());
    }

    public function updateGender(GenderModel $genderModel, GenderDTO $genderData): GenderModel
    {
        $genderModel->update($genderData->toArray());
        return $genderModel;
    }
}
