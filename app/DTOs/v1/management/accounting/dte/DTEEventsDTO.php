<?php

namespace App\DTOs\v1\management\accounting\dte;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class DTEEventsDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $dte_id,

        #[Required, StringType, Max(36)]
        public readonly string $generation_code,

        #[Required, StringType, Max(40)]
        public readonly string $reception_stamp,

        #[Required]
        public readonly Carbon $generation_datetime,

        #[Required, IntegerType]
        public readonly int    $user_id,

        #[Required, ArrayType]
        public readonly array  $json_body,

        #[Required, BooleanType]
        public readonly bool   $status_id,

        #[Required, IntegerType]
        public readonly int    $event_type_id,
    )
    {
    }
}
