<?php

namespace App\Enums\v1\Supports;

use function Symfony\Component\String\s;

enum SupportStatus: int
{
    case PENDING = 1;
    case ASSIGNED = 2;
    case ENDED = 3;
    case CANCELLED = 4;
    case OBSERVED = 5;

    public function getName(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::ASSIGNED => 'Asignado',
            self::ENDED => 'Finalizado',
            self::CANCELLED => 'Cancelado',
            self::OBSERVED => 'Observado',
        };
    }

    public function getBadgeColor(): string
    {
        return match ($this) {
            self::PENDING => 'grey-9',
            self::ASSIGNED => 'blue-9',
            self::ENDED => 'green-10',
            self::CANCELLED => 'red-10',
            self::OBSERVED => 'teal-9'
        };
    }

    public function requiresTechnicians(): bool
    {
        return match ($this) {
            self::ASSIGNED,
            self::ENDED,
            self::OBSERVED => true,
            default => false,
        };
    }

    public function requiresSolution(): bool
    {
        return $this === self::ENDED;
    }

    public function requiresComments(): bool
    {
        return match ($this) {
            self::CANCELLED => true,
            self::OBSERVED => true,
            default => false,
        };
    }

    public function isClosed(): bool
    {
        return match ($this) {
            self::ENDED,
            self::CANCELLED,
            self::OBSERVED => true,
            default => false
        };
    }

    public function isActive(): bool
    {
        return !$this->isClosed();
    }

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function getActiveStatuses(): array
    {
        return [
            self::PENDING->value,
            self::ASSIGNED->value,
        ];
    }

    public static function getClosedStatuses(): array
    {
        return [
            self::ENDED->value,
            self::CANCELLED->value,
            self::OBSERVED->value,
        ];
    }

    public function isFinalized(): bool
    {
        return $this === self::ENDED;
    }
}
