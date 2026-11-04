<?php

namespace App\Services\v1\management\supports;

use App\DTOs\v1\management\supports\StatusDTO;
use App\Models\Supports\StatusModel;

class StatusService
{
    public function create(StatusDTO $DTO): StatusModel
    {
        return StatusModel::query()->create($DTO->toArray());
    }

    public function read(int $id): StatusModel
    {
        return StatusModel::query()->find($id);
    }

    public function update(StatusModel $model, StatusDTO $DTO): StatusModel
    {
        $model->update($DTO->toArray());
        return $model;
    }
}
