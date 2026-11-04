<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\ContractDTO;
use App\Models\Clients\ContractModel;

class ContractService
{
    public function create(ContractDTO $contractDTO): ContractModel
    {
        return ContractModel::create($contractDTO->toArray());
    }

    public function update(ContractModel $contractModel, ContractDTO $contractDTO): ContractModel
    {
        $contractModel->update($contractDTO->toArray());
        return $contractModel;
    }
}
