<?php

namespace App\Http\Resources\v1\management\clients;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'neighborhood' => $this->neighborhood,
            'address' => $this->address,
            'state_id' => $this->state_id,
            'municipality_id' => $this->municipality_id,
            'district_id' => $this->district_id,
            'country_id' => $this->country_id,
            'status_id' => $this->status_id,
        ];
    }
}
