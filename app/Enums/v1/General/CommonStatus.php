<?php

namespace App\Enums\v1\General;

enum CommonStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getName(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activo',
            self::INACTIVE => 'Inactivo',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }
}
