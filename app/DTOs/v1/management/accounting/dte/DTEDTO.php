<?php

namespace App\DTOs\v1\management\accounting\dte;

use App\Enums\v1\Accounting\InvoiceCategories;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class DTEDTO extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public readonly int               $client_id,

        #[Required, IntegerType]
        public readonly int               $document_type_id,

        #[Required, StringType, Max(31)]
        public readonly string            $control_number,

        #[Required, StringType, Max(36)]
        public readonly string            $generation_code,

        #[Required, StringType, Max(40)]
        public readonly string            $reception_stamp,

        #[Required]
        public readonly Carbon            $generation_datetime,

        #[Required, Numeric]
        public readonly float             $total_amount,

        #[Nullable, IntegerType]
        public readonly ?int              $payment_id,

        #[Required]
        public readonly InvoiceCategories $invoice_category,

        #[Nullable, ArrayType]
        public readonly ?array            $invoice_ids,

        #[Nullable, IntegerType]
        public readonly ?int              $other_invoice_id,

        #[Required, IntegerType]
        public readonly int               $user_id,

        #[Required, IntegerType]
        public readonly int               $status_id,

        #[Required, ArrayType]
        public readonly array             $json_body,
    )
    {
    }

    public function toModelAttributes(): array
    {
        return [
            ...$this->toArray(),
            'invoice_category' => $this->invoice_category->value,
        ];
    }

    public static function rules(): array
    {
        return [
            'invoice_ids' => [
                'nullable',
                'required_if:invoice_category,1',
                'array',
            ],
            'invoice_ids*' => [
                'integer',
                'exists:billing_invoices,id',
            ],
            'other_invoice_id' => [
                'nullable',
                'required_if:invoice_category,2'
            ],
        ];
    }
}
