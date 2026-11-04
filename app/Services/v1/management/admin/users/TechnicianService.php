<?php

namespace App\Services\v1\management\admin\users;

use App\DTOs\v1\management\admin\users\TechnicianDTO;
use App\Models\Management\TechnicianModel;

class TechnicianService
{
    public function create(TechnicianDTO $DTO): TechnicianModel
    {
        return TechnicianModel::query()->create($DTO->toArray());
    }

    public function read(int $id): TechnicianModel
    {
        return TechnicianModel::query()->with('user:id,name')->findOrFail($id);
    }

    public function update(TechnicianModel $model, TechnicianDTO $DTO): TechnicianModel
    {
        $model->update($DTO->toArray());
        return $model;
    }
}
