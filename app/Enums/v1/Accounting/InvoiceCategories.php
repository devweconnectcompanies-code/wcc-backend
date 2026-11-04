<?php

namespace App\Enums\v1\Accounting;

enum InvoiceCategories: int
{
    case INVOICE = 1;
    case OTHER_INVOICE = 2;

    public function label(): string
    {
        return match ($this) {
            self::INVOICE => 'Factura de servicio de internet',
            self::OTHER_INVOICE => 'Factura por otros servicios',
        };
    }
}
