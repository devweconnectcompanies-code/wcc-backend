<?php

namespace App\DTOs\v1\management\admin\users;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class PermissionDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $guard_name,

        #[Required, IntegerType]
        public readonly int    $menu_id
    )
    {
    }
}
