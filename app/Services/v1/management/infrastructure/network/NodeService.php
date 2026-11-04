<?php

namespace App\Services\v1\management\infrastructure\network;

use App\Models\Infrastructure\Network\NodeModel;
use App\DTOs\v1\management\infrastructure\network\NodeDTO;

class NodeService
{
    public function create(NodeDTO $dto): NodeModel
    {
        return NodeModel::create($dto->toArray());
    }

    public function update(NodeModel $model, NodeDTO $dto): NodeModel
    {
        $model->update($dto->toArray());
        return $model;
    }
}
