<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ReferenceDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $client_id,
        #[Required, StringType]
        public readonly string $name,
        #[Required, StringType]
        public readonly string $dui,
        #[Required, StringType]
        public readonly string $mobile,
        #[Required, IntegerType]
        public readonly int    $kinship_id,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }

    public function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'] ?? 0,
            name: $data['name'] ?? '',
            dui: $data['dui'] ?? '',
            mobile: $data['mobile'] ?? '',
            kinship_id: $data['kinship_id'] ?? 0,
            status_id: $data['status_id'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id,
            'name' => $this->name,
            'dui' => $this->dui,
            'mobile' => $this->mobile,
            'kinship_id' => $this->kinship_id,
            'status_id' => $this->status_id,
        ];
    }
}
