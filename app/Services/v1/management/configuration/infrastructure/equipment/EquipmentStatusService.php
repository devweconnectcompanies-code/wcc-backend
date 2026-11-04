<?php

namespace App\Services\v1\management\configuration\infrastructure\equipment;

use App\DTOs\v1\management\configuration\infrastructure\equipment\EquipmentStatusDTO;
use App\Models\Configuration\Infrastructure\EquipmentStatusModel;

class EquipmentStatusService
{
    public function create(EquipmentStatusDTO $equipmentStatusDTO): EquipmentStatusModel
    {
        return EquipmentStatusModel::query()->create($equipmentStatusDTO->toArray());
    }

    public function update(EquipmentStatusModel $equipmentStatusModel, EquipmentStatusDTO $equipmentStatusDTO): EquipmentStatusModel
    {
        $equipmentStatusModel->update($equipmentStatusDTO->toArray());
        return $equipmentStatusModel;
    }
}
