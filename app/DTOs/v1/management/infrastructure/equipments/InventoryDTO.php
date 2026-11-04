<?php

namespace App\DTOs\v1\management\infrastructure\equipments;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\MacAddress;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class InventoryDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $brand_id,

        #[Required, IntegerType]
        public readonly int     $type_id,

        #[Required, IntegerType]
        public readonly int     $model_id,

//        #[Nullable, IntegerType]
//        public readonly ?int    $service_id,

        #[Required, IntegerType]
        public readonly int     $branch_id,

        #[Required, MacAddress]
        public readonly string  $mac_address,

        #[Required, StringType]
        public readonly string  $serial_number,

        #[Required, Date]
        public readonly Carbon  $registration_date,

//        #[Nullable, Date]
//        public readonly ?Carbon $departure_date,

//        #[Required, IntegerType]
//        public readonly int     $user_id,

//        #[Nullable, IntegerType]
//        public readonly ?int    $technician_id,

        #[Required, IntegerType]
        public readonly int     $status_id,

        #[Nullable, StringType]
        public readonly ?string $comments,

        #[Required, IntegerType]
        public readonly int     $company_id,
    )
    {
    }
}
