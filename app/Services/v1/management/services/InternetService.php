<?php

namespace App\Services\v1\management\services;

use App\Models\Services\ServiceInternetModel;
use App\DTOs\v1\management\services\ServiceInternetDTO;

class InternetService
{
    public function create(ServiceInternetDTO $internetDTO): ServiceInternetModel
    {
        return ServiceInternetModel::query()->create($internetDTO->toArray());
    }

    public function read(int $id): ?ServiceInternetModel
    {
        return ServiceInternetModel::query()->where('service_id', $id)->first();
    }

    public function update(ServiceInternetModel $serviceInternetModel, ServiceInternetDTO $internetDTO): ServiceInternetModel
    {
        $serviceInternetModel->update($internetDTO->toArray());
        return $serviceInternetModel;
    }
}
