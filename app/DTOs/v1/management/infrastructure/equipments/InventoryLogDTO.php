<?php

namespace App\DTOs\v1\management\infrastructure\equipments;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class InventoryLogDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $equipment_id,

        #[Required, IntegerType]
        public readonly int     $user_id,

        #[Nullable, IntegerType]
        public readonly ?int    $technician_id,

        #[Required, Date]
        public readonly ?Carbon $execution_date,

        #[Nullable, IntegerType]
        public readonly ?int    $service_id,

        #[Required, IntegerType]
        public readonly int     $status_id,

        #[Required, StringType]
        public readonly ?string $description,
    )
    {
    }
}
