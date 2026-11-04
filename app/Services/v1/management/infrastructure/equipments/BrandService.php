<?php

namespace App\Services\v1\management\infrastructure\equipments;

use App\DTOs\v1\management\infrastructure\equipments\BrandDTO;
use App\Models\Infrastructure\Equipment\BrandModel;

class BrandService
{
    public function create(BrandDTO $brandDTO): BrandModel
    {
        return BrandModel::query()->create($brandDTO->toArray());
    }

    public function update(BrandModel $brandModel, BrandDTO $brandDTO): BrandModel
    {
        $brandModel->update($brandDTO->toArray());
        return $brandModel;
    }
}
