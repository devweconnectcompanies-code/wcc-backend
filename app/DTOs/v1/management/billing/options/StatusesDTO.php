<?php

namespace App\DTOs\v1\management\billing\options;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class StatusesDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $badge_color,

        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }
}
