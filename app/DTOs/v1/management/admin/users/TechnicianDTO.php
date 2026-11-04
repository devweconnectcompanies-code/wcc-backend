<?php

namespace App\DTOs\v1\management\admin\users;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Carbon\Carbon;

class TechnicianDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $user_id,

        #[Required, StringType]
        public readonly string $phone_number,

        #[Required, IntegerType]
        public readonly string $status_id,

        #[Required, Date]
        public readonly Carbon $hiring_date,

    )
    {

    }
}
