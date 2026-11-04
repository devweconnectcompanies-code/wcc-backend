<?php

namespace App\DTOs\v1\management\configuration\infrastructure\equipment;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

class EquipmentStatusDTO extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,
        #[Required, StringType]
        public readonly string $badge_color,
        #[Required, IntegerType]
        public readonly int    $status_id,
        #[Required, StringType]
        public readonly string $description,
    )
    {
    }
}
