<?php

namespace App\DTOs\v1\management\services;

use App\Cast\v1\management\Services\EmptyStringToNullCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ServiceDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $client_id,

        #[Nullable, StringType, WithCast(EmptyStringToNullCast::class)]
        public readonly ?string $code,

        #[Nullable, StringType, WithCast(EmptyStringToNullCast::class)]
        public readonly ?string $name,

        #[Required, IntegerType]
        public readonly int     $node_id,

        #[Required, IntegerType]
        public readonly int     $equipment_id,

        #[Required, Date]
        public readonly Carbon  $installation_date,

        #[Required, IntegerType]
        public readonly int     $technician_id,

        #[Required, Numeric]
        public readonly float   $latitude,

        #[Required, Numeric]
        public readonly float   $longitude,

        #[Required, IntegerType]
        public readonly int     $state_id,

        #[Required, IntegerType]
        public readonly int     $municipality_id,

        #[Required, IntegerType]
        public readonly int     $district_id,

        #[Required, StringType]
        public readonly string  $address,

        #[Required, IntegerType]
        public readonly int     $separate_billing,

        #[Required, IntegerType]
        public readonly int     $status_id,

        #[Nullable, WithCast(EmptyStringToNullCast::class)]
        public readonly ?string $comments,
    )
    {

    }
}
