<?php

namespace App\Enums\v1\Billing;

enum ServiceChangeEventTypesEnum: int
{
    case PLAN_CHANGE = 1;
    case UNINSTALLATION = 2;
    case REACTIVATION = 3;

    public function label(): string
    {
        return match ($this) {
            self::PLAN_CHANGE => 'Cambio de plan',
            self::UNINSTALLATION => 'Desinstalación',
            self::REACTIVATION => 'Reactivación del servicio',
        };
    }
}
