<?php

namespace App\Enums\v1\General;

enum ContractStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case CANCELLED = 3;
    case EXPIRED = 4;
    case UNDER_REVIEW = 5;
    case RENEWED = 6;

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }
}
