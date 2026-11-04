<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Carbon\Carbon;

class DocumentDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly ?int    $client_id,
        #[Required, IntegerType]
        public readonly ?int    $document_type_id,
        #[Required, StringType]
        public readonly ?string $number,
        #[Required, Date]
        public readonly ?Carbon $expiration_date,
        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {
    }

    public function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'],
            document_type_id: $data['document_type_id'],
            number: $data['number'],
            expiration_date: isset($data['expiration_date']) ? Carbon::parse($data['expiration_date']) : null,
            status_id: $data['status_id']
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id,
            'document_type_id' => $this->document_type_id,
            'number' => $this->number,
            'expiration_date' => $this->expiration_date->format('Y-m-d'),
            'status_id' => $this->status_id,
        ];
    }
}
