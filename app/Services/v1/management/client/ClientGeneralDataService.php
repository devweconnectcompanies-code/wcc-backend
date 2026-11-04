<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\ClientDTO;
use App\Models\Clients\ClientModel;
use Illuminate\Support\Collection;

class ClientGeneralDataService
{
    public function createClient(ClientDTO $clientData): ClientModel
    {
        return ClientModel::create($clientData->toArray());
    }

    public function updateClient(ClientModel $clientModel, ClientDTO $clientDTOData): ClientModel
    {
        $clientModel->update($clientDTOData->toArray());
        return $clientModel;
    }

    public function getClientBranch(int $id): array
    {
        $query = ClientModel::query()
            ->with('branch')
            ->find($id);

        return [
            'id' => $query->branch?->id,
            'name' => $query->branch?->name,
        ];
    }
}
