<?php

namespace App\DTOs\v1\management\infrastructure\network;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\MacAddress;
use Spatie\LaravelData\Attributes\Validation\IPv4;
use Spatie\LaravelData\Attributes\Validation\Nullable;

use Spatie\LaravelData\Data;

class EquipmentDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,

        #[Required, IntegerType]
        public readonly int     $type_id,

        #[Required, IntegerType]
        public readonly int     $brand_id,

        #[Required, IntegerType]
        public readonly int     $model_id,

        #[Required, MacAddress]
        public readonly string  $mac_address,

        #[Required, IPv4]
        public readonly string  $ip_address,

        #[Required, StringType]
        public readonly string  $username,

        #[Required, StringType]
        public readonly string  $secret,

        #[Required, IntegerType]
        public readonly int     $node_id,

        #[Nullable, StringType]
        public readonly ?string $comments,

        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {
    }
}
