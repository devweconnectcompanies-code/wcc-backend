<?php

namespace App\DTOs\v1\management\services;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class ServiceEquipmentDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int $equipment_id,

        #[Required, IntegerType]
        public readonly int $service_id,

        #[Required, IntegerType]
        public readonly int $status_id,
    )
    {

    }
}
