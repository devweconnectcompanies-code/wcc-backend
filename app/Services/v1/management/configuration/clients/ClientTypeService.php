<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\ClientTypeDTO;
use App\Models\Configuration\Clients\ClientTypeModel;

class ClientTypeService
{
    public function createType(ClientTypeDTO $clientTypeData): ClientTypeModel
    {
        return ClientTypeModel::create($clientTypeData->toArray());
    }

    public function updateType(ClientTypeModel $clientTypeModel, ClientTypeDTO $clientTypeData): ClientTypeModel
    {
        $clientTypeModel->update($clientTypeData->toArray());
        return $clientTypeModel;
    }
}
