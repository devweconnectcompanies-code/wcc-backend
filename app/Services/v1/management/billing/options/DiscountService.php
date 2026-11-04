<?php

namespace App\Services\v1\management\billing\options;

use App\DTOs\v1\management\billing\options\DiscountDTO;
use App\Models\Billing\DiscountModel;

class DiscountService
{
    public function createDiscount(DiscountDTO $discountDTO): DiscountModel
    {
        return DiscountModel::query()->create($discountDTO->toArray());
    }

    public function editDiscount(int $id): DiscountModel
    {
        return DiscountModel::query()->findOrFail($id);
    }

    public function updateDiscount(DiscountModel $model, DiscountDTO $discountDTO): DiscountModel
    {
        $model->update($discountDTO->toArray());
        return $model->refresh();
    }
}
