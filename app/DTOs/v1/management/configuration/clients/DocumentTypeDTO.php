<?php

namespace App\DTOs\v1\management\configuration\clients;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

class DocumentTypeDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public ?string $name,

        #[Required, StringType]
        public ?string $code,

        #[Required, IntegerType]
        public ?int    $status_id,
    )
    {

    }
}
