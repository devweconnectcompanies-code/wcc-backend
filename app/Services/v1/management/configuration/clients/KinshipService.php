<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\KinshipDTO;
use App\Models\Configuration\Clients\KinshipModel;

class KinshipService
{
    public function createKinship(KinshipDTO $data): KinshipModel
    {
        return KinshipModel::create($data->toArray());
    }

    public function updateKinship(KinshipModel $model, KinshipDTO $data): KinshipModel
    {
        $model->update($data->toArray());
        return $model;
    }
}
