<?php

namespace App\DTOs\v1\management\services;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ServiceIptvEquipmentDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $equipment_id,

        #[Required, IntegerType]
        public readonly string  $service_id,

        #[Required, Email]
        public readonly string  $email,

//        #[Nullable]
//        public readonly ?int    $email_correlative,

        #[Required, StringType]
        public readonly string  $email_password,

        #[Required, StringType]
        public readonly string  $iptv_password,

        #[Nullable, StringType]
        public readonly ?string $comments,

        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {

    }
}
