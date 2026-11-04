<?php

namespace App\Enums\v1\Accounting\DTE;

enum EventTypes: int
{
    case INVALIDACION = 1;
    case GESTION = 2;
    case OPERACIONES_ESPECIALES = 3;
    case RETORNO = 4;

    public function label(): string
    {
        return match ($this) {
            self::INVALIDACION => 'Invalidación',
            self::GESTION => 'Gestión',
            self::OPERACIONES_ESPECIALES => 'Operaciones especiales',
            self::RETORNO => 'Retorno',
        };
    }

    public function code(): string
    {
        return match ($this) {
            self::INVALIDACION => '12',
            self::GESTION => '16',
            self::OPERACIONES_ESPECIALES => '17',
            self::RETORNO => '18',
        };
    }

    public function folderName(): string
    {
        return $this->name;
    }
}
