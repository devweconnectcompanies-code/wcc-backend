<?php

namespace App\Services\v1\management\billing\options;

use App\DTOs\v1\management\billing\options\PaymentMethodDTO;
use App\Models\Billing\Options\PaymentMethodModel;

class PaymentMethodService
{
    public function storeMethod(PaymentMethodDTO $DTO): PaymentMethodModel
    {
        return PaymentMethodModel::query()->create($DTO->toArray());
    }

    public function editMethod(int $id): PaymentMethodModel
    {
        return PaymentMethodModel::query()->findOrFail($id);
    }

    public function updateMethod(PaymentMethodModel $model, PaymentMethodDTO $DTO): PaymentMethodModel
    {
        $model->update($DTO->toArray());
        return $model->refresh();
    }
}
