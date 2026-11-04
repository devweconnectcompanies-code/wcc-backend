<?php

namespace App\DTOs\v1\management\configuration\menu;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class MenuDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $url,

        #[Required, StringType]
        public readonly string $icon,

        #[Required, IntegerType]
        public readonly int    $parent_id,

        #[Required, IntegerType]
        public readonly int    $order,

        #[Required, IntegerType]
        public readonly int    $status_id,

        #[Required, StringType]
        public readonly string $slug,
    )
    {
    }
}
