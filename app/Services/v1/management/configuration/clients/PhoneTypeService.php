<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\PhoneTypeDTO;
use App\Models\Configuration\Clients\PhoneTypeModel;

class PhoneTypeService
{
    public function createType(PhoneTypeDTO $data): PhoneTypeModel
    {
        return PhoneTypeModel::create($data->toArray());
    }

    public function updateType(PhoneTypeModel $model, PhoneTypeDTO $data): PhoneTypeModel
    {
        $model->update($data->toArray());
        return $model;
    }
}
