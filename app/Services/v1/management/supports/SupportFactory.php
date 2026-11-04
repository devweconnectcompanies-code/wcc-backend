<?php

namespace App\Services\v1\management\supports;

use App\Contracts\v1\Supports\SupportTypeInterface;
use App\Strategies\v1\Supports\Process\ChangeAddress;
use App\Strategies\v1\Supports\Process\EquipmentSale;
use App\Strategies\v1\Supports\Process\Installation;
use App\Strategies\v1\Supports\Process\Maintenance;
use App\Strategies\v1\Supports\Process\Uninstall;
use InvalidArgumentException;

class SupportFactory
{
    protected static array $map = [
        1 => Installation::class,
        2 => Installation::class,
        3 => Maintenance::class,
        4 => Maintenance::class,
        5 => ChangeAddress::class,
        6 => Installation::class,
        7 => Installation::class,
        8 => Uninstall::class,
        9 => EquipmentSale::class,
    ];

    public static function make(int $type): SupportTypeInterface
    {
        if (!isset(self::$map[$type])) {
            throw new InvalidArgumentException("Tipo de soporte no válido.");
        }

        return app(self::$map[$type]);
    }
}
