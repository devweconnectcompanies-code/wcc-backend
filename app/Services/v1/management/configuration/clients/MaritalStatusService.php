<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\MaritalStatusDTO;
use App\Models\Configuration\Clients\MaritalStatusModel;

class MaritalStatusService
{
    public function createMaritalStatus(MaritalStatusDTO $maritalStatusData): MaritalStatusModel
    {
        return MaritalStatusModel::create($maritalStatusData->toArray());
    }

    public function updateMaritalStatus(MaritalStatusModel $maritalStatusModel, MaritalStatusDTO $maritalStatusData): MaritalStatusModel
    {
        $maritalStatusModel->update($maritalStatusData->toArray());
        return $maritalStatusModel;
    }
}
