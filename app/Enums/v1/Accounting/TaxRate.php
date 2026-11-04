<?php

namespace App\Enums\v1\Accounting;

enum TaxRate
{
    case VALOR_NETO;
    case IVA;
    case IVA_RETENIDO;

    public function value(): float
    {
        return match ($this) {
            self::VALOR_NETO => 1.13,
            self::IVA => 0.13,
            self::IVA_RETENIDO => 0.01,
        };
    }
}
