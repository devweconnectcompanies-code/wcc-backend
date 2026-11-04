<?php

namespace App\DTOs\v1\management\billing\extensions;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ExtensionDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $invoiceId,

        #[Required, Date]
        public readonly Carbon $previousDueDate,

        #[Required, IntegerType]
        public readonly int    $days,

        #[Required, StringType]
        public readonly string $reason,

        #[Required, IntegerType]
        public readonly int    $user_id,

        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {
    }
}
