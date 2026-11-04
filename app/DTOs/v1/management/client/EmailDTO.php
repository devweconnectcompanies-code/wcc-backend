<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class EmailDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $client_id,
        #[Required, Email]
        public readonly string $email,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }

    public function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'] ?? 0,
            email: $data['email'],
            status_id: $data['status_id'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id ?? 0,
            'email' => $this->email ?? 'mail@mail.com',
            'status_id' => $this->status_id ?? 0,
        ];
    }
}
