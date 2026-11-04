<?php

namespace App\DTOs\v1\management\infrastructure\network;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\IP;

class AuthServerDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,
        #[Required, StringType]
        public readonly string $user,
        #[Required, StringType]
        public readonly string $secret,
        #[Required, IP]
        public readonly string $ip,
        #[Required, IntegerType]
        public readonly int    $port,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {
    }
}
