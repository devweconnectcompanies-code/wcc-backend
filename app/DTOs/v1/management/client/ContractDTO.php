<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Carbon\Carbon;

class ContractDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int    $client_id,
        #[Required, Date]
        public readonly Carbon $contract_date,
        #[Required, Date]
        public readonly Carbon $contract_end_date,
        #[Required]
        public readonly float  $installation_price,
        #[Required]
        public readonly float  $contract_amount,
        #[Required, IntegerType]
        public readonly int    $contract_status_id,
        #[Required, IntegerType]
        public readonly int    $status_id,
    )
    {

    }

    public function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'] ?? 0,
            contract_date: isset($data['contract_date']) ? Carbon::parse($data['contract_date']) : null,
            contract_end_date: isset($data['contract_end_date']) ? Carbon::parse($data['contract_end_date']) : null,
            installation_price: $data['installation_price'] ?? 25.00,
            contract_amount: $data['contract_amount'] ?? 0,
            contract_status_id: $data['contract_status_id'] ?? 0,
            status_id: $data['status_id'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id,
            'contract_date' => $this->contract_date->format('Y-m-d'),
            'contract_end_date' => $this->contract_end_date->format('Y-m-d'),
            'installation_price' => $this->installation_price,
            'contract_amount' => $this->contract_amount,
            'contract_status_id' => $this->contract_status_id,
            'status_id' => $this->status_id,
        ];
    }
}
