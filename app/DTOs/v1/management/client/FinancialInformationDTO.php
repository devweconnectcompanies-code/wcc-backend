<?php

namespace App\DTOs\v1\management\client;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class FinancialInformationDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int     $client_id,
        #[Required, StringType]
        public readonly string  $nrc,
        #[Required, IntegerType]
        public readonly int     $activity_id,
        #[Required, IntegerType]
        public readonly int     $retained_iva,
        #[Required, StringType]
        public readonly string  $legal_representative,
        #[Required, StringType]
        public readonly string  $dui,
        #[Required, StringType]
        public readonly string  $nit,
        #[Required, StringType]
        public readonly string  $phone_number,

        public readonly ?string $invoice_alias,
        #[Required, IntegerType]
        public readonly int     $state_id,
        #[Required, IntegerType]
        public readonly int     $municipality_id,
        #[Required, IntegerType]
        public readonly int     $district_id,
        #[Required, StringType]
        public readonly string  $address,
        #[Required, IntegerType]
        public readonly int     $status_id,
    )
    {

    }

    public function fromArray(array $data): self
    {
        return new self(
            client_id: $data['client_id'] ?? 0,
            nrc: $data['nrc'] ?? '',
            activity_id: $data['activity_id'] ?? 0,
            retained_iva: $data['retained_iva'] ?? 0,
            legal_representative: $data['legal_representative'] ?? '',
            dui: $data['dui'] ?? '',
            nit: $data['nit'] ?? '',
            phone_number: $data['phone_number'] ?? '',
            invoice_alias: $data['invoice_alias'] ?? '',
            state_id: $data['state_id'] ?? 0,
            municipality_id: $data['municipality_id'] ?? 0,
            district_id: $data['district_id'] ?? 0,
            address: $data['address'] ?? '',
            status_id: $data['status_id'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id,
            'nrc' => $this->nrc,
            'activity_id' => $this->activity_id,
            'retained_iva' => $this->retained_iva,
            'legal_representative' => $this->legal_representative,
            'dui' => $this->dui,
            'nit' => $this->nit,
            'phone_number' => $this->phone_number,
            'invoice_alias' => $this->invoice_alias,
            'state_id' => $this->state_id,
            'municipality_id' => $this->municipality_id,
            'district_id' => $this->district_id,
            'address' => $this->address,
            'status_id' => $this->status_id,
        ];
    }
}
