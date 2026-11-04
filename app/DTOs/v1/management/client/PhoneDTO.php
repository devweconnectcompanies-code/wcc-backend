<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class PhoneDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $client_id,
        #[Required, IntegerType]
        public readonly int    $phone_type_id,
        #[Required, StringType]
        public readonly string $number,
        #[Required, IntegerType]
        public readonly int    $status_id,
        #[Required, StringType]
        public readonly string $country_code,
    )
    {

    }

    public
    function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'] ?? 0,
            phone_type_id: $data['phone_type_id'] ?? 0,
            number: $data['number'] ?? '',
            status_id: $data['status_id'] ?? 0,
            country_code: $data['country_code'] ?? null,
        );
    }

    public
    function toArray(): array
    {
        return [
            'client_id' => $this->client_id ?? 0,
            'phone_type_id' => $this->phone_type_id ?? 0,
            'number' => $this->number ?? '',
            'status_id' => $this->status_id ?? 0,
            'country_code' => $this->country_code ?? null,
        ];
    }
}
