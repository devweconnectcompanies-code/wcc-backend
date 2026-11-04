<?php

namespace App\Services\v1\management\client;

use App\Models\Clients\ReferenceModel;
use App\DTOs\v1\management\client\ReferenceDTO;

class ReferenceService
{
    public function createReference(ReferenceDTO $data): ReferenceModel
    {
        return ReferenceModel::create($data->toArray());
    }

    public function updateReference(ReferenceModel $model, ReferenceDTO $data): ReferenceModel
    {
        $model->update($data->toArray());
        return $model;
    }
}
