<?php

namespace App\Services\v1\management\infrastructure\network;

use App\DTOs\v1\management\infrastructure\network\EquipmentDTO;
use App\Models\Infrastructure\Network\EquipmentModel;

class EquipmentService
{
    public function create(EquipmentDTO $DTO): EquipmentModel
    {
        return EquipmentModel::query()->create($DTO->toArray());
    }

    public function find(int $id): EquipmentModel
    {
        return EquipmentModel::query()->find($id);
    }

    public function update(EquipmentModel $equipmentModel, EquipmentDTO $DTO): EquipmentModel
    {
        $equipmentModel->update($DTO->toArray());
        return $equipmentModel;
    }
}
