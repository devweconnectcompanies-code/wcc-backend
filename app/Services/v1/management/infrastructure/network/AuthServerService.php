<?php

namespace App\Services\v1\management\infrastructure\network;

use App\DTOs\v1\management\infrastructure\network\AuthServerDTO;
use App\Models\Infrastructure\Network\AuthServerModel;

class AuthServerService
{
    public function create(AuthServerDTO $authServerDTO): AuthServerModel
    {
        return AuthServerModel::create($authServerDTO->toArray());
    }

    public function update(AuthServerModel $authServer, AuthServerDTO $authServerDTO): AuthServerModel
    {
        $authServer->update($authServerDTO->toArray());
        return $authServer;
    }
}
