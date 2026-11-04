<?php

namespace App\Http\Resources\v1\management\configuration\branch;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'code' => $this->code,
            'landline' => $this->landline,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'state_id' => $this->state_id,
            'municipality_id' => $this->municipality_id,
            'district_id' => $this->district_id,
            'country_id' => $this->country_id,
            'badge_color' => $this->badge_color,
            'status_id' => $this->status_id,
        ];
    }
}
