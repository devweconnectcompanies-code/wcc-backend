<?php

namespace App\Http\Resources\v1\management\clients;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'document_type_id' => $this->document_type_id,
            'number' => $this->number,
            'expiration_date' => $this->expiration_date,
            'status_id' => $this->status_id,
        ];
    }
}
