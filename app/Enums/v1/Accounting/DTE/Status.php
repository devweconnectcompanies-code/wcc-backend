<?php

namespace App\Enums\v1\Accounting\DTE;

enum Status: int
{
    case EMITIDO = 1;
    case ANULADO = 2;
    case CON_DEVOLUCION = 3;

    public function labels(): string
    {
        return match ($this) {
            self::EMITIDO => 'Emitido',
            self::ANULADO => 'Anulado',
            self::CON_DEVOLUCION => 'Con Devolución',
        };
    }
}
