<?php

namespace App\DTOs\v1\management\configuration\branches;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

class BranchDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,

        #[Nullable, StringType]
        public readonly ?string $code,

        #[Required, StringType]
        public readonly string  $landline,

        #[Required, StringType]
        public readonly string  $mobile,

        #[Required, StringType]
        public readonly string  $address,

        #[Required, IntegerType]
        public readonly int     $state_id,

        #[Required, IntegerType]
        public readonly int     $municipality_id,

        #[Required, IntegerType]
        public readonly int     $district_id,

        #[Required, IntegerType]
        public readonly int     $country_id,

        #[Required, StringType]
        public readonly string  $badge_color,

        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {
    }
}
