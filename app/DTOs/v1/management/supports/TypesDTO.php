<?php

namespace App\DTOs\v1\management\supports;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class TypesDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,

        #[Nullable, StringType]
        public readonly ?string $badge_color,

        #[Required, IntegerType]
        public readonly int     $status_id,

        #[Required, Numeric]
        public readonly float   $price,
    )
    {
    }
}
