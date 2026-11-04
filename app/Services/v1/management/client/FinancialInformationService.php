<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\FinancialInformationDTO;
use App\Models\Clients\FinancialInformationModel;

class FinancialInformationService
{
    public function createInformation(FinancialInformationDTO $financialInformationDTO): FinancialInformationModel
    {
        return FinancialInformationModel::create($financialInformationDTO->toArray());
    }

    public function updateInformation(FinancialInformationModel $financialInformationModel, FinancialInformationDTO $dto): FinancialInformationModel
    {
        $financialInformationModel->update($dto->toArray());
        return $financialInformationModel;
    }
}
