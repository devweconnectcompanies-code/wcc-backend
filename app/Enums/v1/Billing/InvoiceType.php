<?php

namespace App\Enums\v1\Billing;

enum InvoiceType: int
{
    case INDIVIDUAL = 1;
    case CONSOLIDATED = 2;

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Individual',
            self::CONSOLIDATED => 'Consolidada',
        };
    }
}
