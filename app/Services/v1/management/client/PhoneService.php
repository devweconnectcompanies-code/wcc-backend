<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\PhoneDTO;
use App\Models\Clients\PhoneModel;

class PhoneService
{
    public function storePhone(PhoneDTO $data): PhoneModel
    {
        return PhoneModel::create($data->toArray());
    }

    public function updatePhone(PhoneModel $model, PhoneDTO $data): PhoneModel
    {
        $model->update($data->toArray());
        return $model;
    }
}
