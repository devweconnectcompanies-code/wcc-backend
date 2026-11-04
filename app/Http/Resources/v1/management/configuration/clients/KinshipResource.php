<?php

namespace App\Http\Resources\v1\management\configuration\clients;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KinshipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status_id' => $this->status_id
        ];
    }
}
