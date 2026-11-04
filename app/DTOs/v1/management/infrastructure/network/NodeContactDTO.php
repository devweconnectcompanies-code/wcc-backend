<?php

namespace App\DTOs\v1\management\infrastructure\network;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;

class NodeContactDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $node_id,
        #[Required, StringType]
        public readonly string $name,
        #[Required, StringType]
        public readonly string $phone_number,
        #[Required, Date]
        public readonly Carbon $initial_contract_date,
        #[Required, Date]
        public readonly Carbon $final_contract_date,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {
    }
}
