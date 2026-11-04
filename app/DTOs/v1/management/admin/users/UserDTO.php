<?php

namespace App\DTOs\v1\management\admin\users;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class UserDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string  $name,

        #[Required, Email]
        public readonly string  $email,

        #[Nullable]
        public readonly ?string $password,

        #[Required, IntegerType]
        public readonly int     $status_id,

        #[Required, IntegerType]
        public readonly int     $role = 2,

        #[ArrayType]
        public readonly array   $permissions = [],
    )
    {

    }
}
