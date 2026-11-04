<?php

namespace App\Services\v1\management\services;

use App\DTOs\v1\management\services\ServiceDTO;
use App\Models\Clients\ClientModel;
use App\Models\Services\ServiceModel;
use Illuminate\Support\Collection;

class ServService
{
    public function create(ServiceDTO $DTO): ServiceModel
    {
        return ServiceModel::query()->create($DTO->toArray());
    }

    public function read(int $id): ServiceModel
    {
        return ServiceModel::query()->find($id);
    }

    public function update(ServiceModel $model, ServiceDTO $DTO): ServiceModel
    {
        $model->update($DTO->toArray());
        return $model;
    }

    public function clientServices(int $id): ClientModel
    {
        return ClientModel::query()->with([
            'services.node:id,name',
            'services.equipment:id,name',
            'services.state:id,name',
            'services.municipality:id,name',
            'services.district:id,name',
            'services.internet.profile',
        ])->find($id);
    }

    public function supportList(int $id): Collection
    {
        $query = ServiceModel::query()
            ->where([
                ['client_id', $id],
                ['status_id', 1]
            ])
            ->get();

        return $query->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => "{$item->id} - {$item->address}",
            ];
        });
    }

    public function getAddress(int $id): ServiceModel
    {
        return ServiceModel::query()
            ->select(['state_id', 'municipality_id', 'district_id', 'address'])
            ->where('id', $id)
            ->first()
            ->makeHidden('status');
    }
}
