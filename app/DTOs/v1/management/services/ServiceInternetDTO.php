<?php

namespace App\DTOs\v1\management\services;


use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ServiceInternetDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $internet_profile_id,

        #[Required, IntegerType]
        public readonly int    $service_id,

        #[Required, StringType]
        public readonly string $user,

        #[Required, StringType]
        public readonly string $secret,

        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }
}
