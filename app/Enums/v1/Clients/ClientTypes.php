<?php

namespace App\Enums\v1\Clients;

enum ClientTypes: int
{
    case RESIDENTIAL = 1;
    case CORPORATE = 2;
    case FREE = 3;

    public function label(): string
    {
        return match ($this) {
            self::RESIDENTIAL => 'Cliente Residencial',
            self::CORPORATE => 'Cliente Corporativo',
            self::FREE => 'Cliente Gratuito',
        };
    }
}
