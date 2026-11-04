<?php

namespace App\DTOs\v1\management\configuration\geography;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

class DistrictsDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly ?string $name,

        #[Required, IntegerType]
        public readonly ?int    $municipality_id,

        #[Required, IntegerType]
        public readonly ?int    $state_id,

        #[Required, IntegerType]
        public readonly ?int    $status_id,

        #[Required, StringType]
        public readonly string  $code,
    )
    {
    }
}
