<?php

namespace App\DTOs\v1\management\billing\payments;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class PaymentDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $client_id,

        #[Required, IntegerType]
        public readonly int     $payment_method_id,

        #[Required, Numeric]
        public readonly float   $amount,

        #[Required, Date]
        public readonly Carbon  $payment_date,

        #[Nullable, StringType]
        public readonly ?string $reference_number,

        #[Required, IntegerType]
        public readonly int     $user_id,

        #[Nullable, StringType]
        public readonly ?string $comments,

        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {
    }
}
