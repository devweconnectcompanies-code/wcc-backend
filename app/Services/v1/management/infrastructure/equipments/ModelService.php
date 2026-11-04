<?php

namespace App\Services\v1\management\infrastructure\equipments;

use App\DTOs\v1\management\infrastructure\equipments\ModelDTO;
use App\Models\Infrastructure\Equipment\ModelModel;

class ModelService
{
    public function create(ModelDTO $dto): ModelModel
    {
        return ModelModel::query()->create($dto->toArray());
    }

    public function update(ModelModel $model, ModelDTO $dto): ModelModel
    {
        $model->update($dto->toArray());
        return $model;
    }
}
