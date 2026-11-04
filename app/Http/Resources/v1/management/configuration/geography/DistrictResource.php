<?php

namespace App\Http\Resources\v1\management\configuration\geography;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'municipality_id' => $this->municipality_id,
            'state_id' => $this->state_id,
            'status_id' => $this->status_id,
        ];
    }
}
