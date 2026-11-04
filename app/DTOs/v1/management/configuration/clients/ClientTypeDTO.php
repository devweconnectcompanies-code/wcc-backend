<?php

namespace App\DTOs\v1\management\configuration\clients;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;


class ClientTypeDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly ?string $name = null,
        #[Required, IntegerType]
        public readonly ?int    $status_id = 0,
    )
    {

    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'status_id' => $this->status_id
        ];
    }
}
