<?php

namespace App\Enums\v1\General;

enum BillingStatus: int
{
    case ISSUED = 1;
    case PENDING = 2;
    case PAID = 3;
    case OVERDUE = 4;
    case CANCELED = 5;
    case PARTIALLY_PAID = 6;

    public function label(): string
    {
        return match ($this) {
            self::ISSUED => 'Emitida',
            self::PENDING => 'Pendiente de pago',
            self::PAID => 'Pagada',
            self::OVERDUE => 'Vencida',
            self::CANCELED => 'Anulada',
            self::PARTIALLY_PAID => 'Parcialmente pagada',
        };
    }
}
