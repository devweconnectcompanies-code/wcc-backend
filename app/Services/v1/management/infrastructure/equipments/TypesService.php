<?php

namespace App\Services\v1\management\infrastructure\equipments;

use App\DTOs\v1\management\infrastructure\equipments\TypesDTO;
use App\Models\Infrastructure\Equipment\TypeModel;

class TypesService
{
    public function create(TypesDTO $DTO): TypeModel
    {
        return TypeModel::query()->create($DTO->toArray());
    }

    public function update(TypeModel $typeModel, TypesDTO $DTO): TypeModel
    {
        $typeModel->update($DTO->toArray());
        return $typeModel;
    }
}
