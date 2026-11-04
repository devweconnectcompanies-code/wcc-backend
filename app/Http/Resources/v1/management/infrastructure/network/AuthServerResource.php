<?php

namespace App\Http\Resources\v1\management\infrastructure\network;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthServerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
