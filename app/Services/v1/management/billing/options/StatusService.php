<?php

namespace App\Services\v1\management\billing\options;

use App\DTOs\v1\management\billing\options\StatusesDTO;
use App\Models\Billing\Options\StatusModel;

class StatusService
{
    public function createStatus(StatusesDTO $dto): StatusModel
    {
        return StatusModel::query()->create($dto->toArray());
    }

    public function editStatus(int $id): StatusModel
    {
        return StatusModel::query()->findOrFail($id);
    }

    public function updateStatus(StatusModel $model, StatusesDTO $dto): StatusModel
    {
        $model->update($dto->toArray());
        return $model->refresh();
    }
}
