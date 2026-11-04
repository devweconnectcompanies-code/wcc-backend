<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

class AddressDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $client_id,
        #[Required, StringType]
        public readonly string $neighborhood,
        #[Required, StringType]
        public readonly string $address,
        #[Required, IntegerType]
        public readonly int    $state_id,
        #[Required, IntegerType]
        public readonly int    $municipality_id,
        #[Required, IntegerType]
        public readonly int    $district_id,
        #[Required, IntegerType]
        public readonly int    $country_id,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }

    public function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'] ?? 0,
            neighborhood: $data['neighborhood'] ?? '',
            address: $data['address'] ?? '',
            state_id: $data['state_id'] ?? 0,
            municipality_id: $data['municipality_id'] ?? 0,
            district_id: $data['district_id'] ?? 0,
            country_id: $data['country_id'] ?? 0,
            status_id: $data['status_id'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
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
