<?php

namespace App\DTOs\v1\management\configuration\clients;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

class PhoneTypeDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly ?string $name,
        #[Required, IntegerType]
        public readonly int     $status_id
    )
    {

    }

    public function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            status_id: $data['status_id'] ?? 0
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name ?? null,
            'status_id' => $this->status_id ?? 0
        ];
    }
}
