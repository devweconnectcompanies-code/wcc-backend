<?php

namespace App\Http\Resources\v1\management\clients;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferencesResource extends JsonResource
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
            'client_id' => $this->client_id,
            'name' => $this->name,
            'dui' => $this->dui,
            'mobile' => $this->mobile,
            'kinship_id' => $this->kinship_id,
            'status_id' => $this->status_id,
        ];
    }
}
