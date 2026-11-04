<?php

namespace App\Http\Resources\v1\management\clients;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialInformationResource extends JsonResource
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
            'nrc' => $this->nrc,
            'activity_id' => $this->activity_id,
            'retained_iva' => $this->retained_iva,
            'legal_representative' => $this->legal_representative,
            'dui' => $this->dui,
            'nit' => $this->nit,
            'phone_number' => $this->phone_number,
            'invoice_alias' => $this->invoice_alias,
            'state_id' => $this->state_id,
            'municipality_id' => $this->municipality_id,
            'district_id' => $this->district_id,
            'address' => $this->address,
            'status_id' => $this->status_id,
        ];
    }
}
