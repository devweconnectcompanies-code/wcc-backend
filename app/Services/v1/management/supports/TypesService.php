<?php

namespace App\Services\v1\management\supports;

use App\DTOs\v1\management\supports\TypesDTO;
use App\Models\Supports\TypeModel;

class TypesService
{
    public function create(TypesDTO $DTO): TypeModel
    {
        return TypeModel::query()->create($DTO->toArray());
    }

    public function read(int $id): TypeModel
    {
        return TypeModel::query()->find($id);
    }

    public function update(TypeModel $model, TypesDTO $DTO): TypeModel
    {
        $model->update($DTO->toArray());
        return $model;
    }
}
