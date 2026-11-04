<?php

namespace App\DTOs\v1\management\supports;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class RatingDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $support_id,

        #[Required, IntegerType]
        public readonly int     $overall_rate,

        #[Required, IntegerType]
        public readonly int     $attention_rate,

        #[Required, IntegerType]
        public readonly int     $solution_rate,

        #[Required, IntegerType]
        public readonly int     $punctuality_rate,

        #[Required, IntegerType]
        public readonly int     $recommendation_rate,

        #[Required, BooleanType]
        public readonly bool    $resolved,

        #[Nullable, StringType]
        public readonly ?string $comment,

        #[Nullable, Date]
        public readonly ?Carbon $survey_datetime
    )
    {
    }
}
