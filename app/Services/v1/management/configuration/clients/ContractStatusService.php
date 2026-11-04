<?php

namespace App\Services\v1\management\configuration\clients;

use App\DTOs\v1\management\configuration\clients\ContractStatusDTO;
use App\Models\Configuration\Clients\ContractStateModel;

class ContractStatusService
{
    public function create(ContractStatusDTO $dto): ContractStateModel
    {
        return ContractStateModel::create($dto->toArray());
    }

    public function update(ContractStateModel $model, ContractStatusDTO $dto): ContractStateModel
    {
        $model->update($dto->toArray());
        return $model;
    }
}
