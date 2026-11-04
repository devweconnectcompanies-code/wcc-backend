<?php

namespace App\DTOs\v1\management\billing\options;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class DiscountDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,

        #[Required, StringType]
        public readonly string  $code,

        #[Nullable, StringType]
        public readonly ?string $description,

        #[Nullable, Numeric]
        public readonly ?float  $percentage,

        #[Nullable, Numeric]
        public readonly ?float  $amount,

        #[Required, Numeric]
        public readonly int     $status_id,
    )
    {
    }
}
