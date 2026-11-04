<?php

namespace App\DTOs\v1\management\infrastructure\network;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

class NodeDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,
        #[Required, StringType]
        public readonly string  $prefix,
        #[Required, IntegerType]
        public readonly int     $server_id,
        #[Required]
        public readonly float   $latitude,
        #[Required]
        public readonly float   $longitude,
        #[Required, IntegerType]
        public readonly int     $state_id,
        #[Required, IntegerType]
        public readonly int     $municipality_id,
        #[Required, IntegerType]
        public readonly int     $district_id,
        #[Required, StringType]
        public readonly string  $address,
        #[Required, StringType]
        public readonly string  $nc,
        #[Required, StringType]
        public readonly string  $nc_owner,
        public readonly ?string $comments,
        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {
    }
}
