<?php

namespace App\Services\v1\management\infrastructure\network;

use App\DTOs\v1\management\infrastructure\network\NodeContactDTO;
use App\Models\Infrastructure\Network\NodeContactModel;

class NodeContactService
{
    public function create(NodeContactDTO $nodeContactDTO): NodeContactModel
    {
        return NodeContactModel::query()->create($nodeContactDTO->toArray());
    }

    public function update(NodeContactModel $nodeContactModel, NodeContactDTO $nodeContactDTO): NodeContactModel
    {
        $nodeContactModel->update($nodeContactDTO->toArray());
        return $nodeContactModel;
    }
}
