<?php

namespace App\DTOs\v1\management\admin\profiles;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class InternetDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,
        #[Required, StringType]
        public readonly string  $alias,
        #[Required, StringType]
        public readonly string  $description,
        #[Required, StringType]
        public readonly string  $mk_profile,
        #[Nullable, StringType]
        public readonly ?string $debt_profile,
        #[Required]
        public readonly float   $net_value,
        #[Required]
        public readonly float   $iva,
        #[Required]
        public readonly float   $price,
        #[Required, Date, WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon  $expiration_date,
        #[Required, IntegerType]
        public readonly int     $status_id,
        #[Required, IntegerType]
        public readonly int     $iptv,
        #[Required, IntegerType]
        public readonly int     $ftth,
        #[Nullable, IntegerType]
        public readonly ?int    $allowed_stb,
    )
    {

    }
}
