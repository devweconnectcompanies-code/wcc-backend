<?php

namespace App\DTOs\v1\management\infrastructure\equipments;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

class TypesDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {
    }
}
