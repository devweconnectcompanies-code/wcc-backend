<?php

namespace App\DTOs\v1\management\supports;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;

class SupportDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $type_id,

        #[Required, StringType]
        public readonly string  $ticket_number,

        #[Required, IntegerType]
        public readonly int     $client_id,

        #[Nullable, IntegerType]
        public readonly ?int    $service_id,

        #[Required, IntegerType]
        public readonly int     $branch_id,

        #[Required, Date]
        public readonly Carbon  $creation_date,

        #[Nullable, Date]
        public readonly ?Carbon $due_date,

        #[Nullable, StringType]
        public readonly ?string $description,

        #[Nullable, IntegerType]
        public readonly ?int    $technician_id,

        #[Required, IntegerType]
        public readonly int     $state_id,

        #[Required, IntegerType]
        public readonly int     $municipality_id,

        #[Required, IntegerType]
        public readonly int     $district_id,

        #[Required, StringType]
        public readonly string  $address,

        #[Nullable, Date]
        public readonly ?Carbon $closed_at,

        #[Nullable, StringType]
        public readonly ?string $solution,

        #[Nullable, StringType]
        public readonly ?string $comments,

        #[Required, IntegerType]
        public readonly int     $user_id,

        #[Required, IntegerType]
        public readonly int     $status_id,

        #[Nullable, IntegerType]
        public readonly ?int    $breached_sla,

        #[Nullable, IntegerType]
        public readonly ?int    $resolution_time,

        /***
         *  Datos para Soportes Detalles
         ***/
        #[Nullable, IntegerType]
        public readonly ?int    $internet_profile_id,

        #[Nullable, IntegerType]
        public readonly ?int    $node_id,

        #[Nullable, IntegerType]
        public readonly ?int    $equipment_id,

        /***************
         *  Datos para Contrato
         ***************/
        #[Nullable, Date]
        public readonly ?Carbon $contract_date,

        #[Nullable, Date]
        public readonly ?Carbon $contract_end_date,
    )
    {

    }
}
