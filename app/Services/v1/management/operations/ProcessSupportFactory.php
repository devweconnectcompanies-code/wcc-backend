<?php

namespace App\Services\v1\management\operations;

use App\Contracts\v1\Supports\ProcessSupportInterface;
use App\Enums\v1\Supports\SupportType;
use App\Strategies\v1\Operations\Technical\ChangeAddressStrategy;
use App\Strategies\v1\Operations\Technical\InstallationStrategy;
use App\Strategies\v1\Operations\Technical\RenovationStrategy;
use App\Strategies\v1\Operations\Technical\SupportsStrategy;
use App\Strategies\v1\Operations\Technical\UninstallationStrategy;

class ProcessSupportFactory
{
    protected static array $map = [
        SupportType::INTERNET_INSTALLATION->value => InstallationStrategy::class,
        SupportType::IPTV_INSTALLATION->value => InstallationStrategy::class,
        SupportType::INTERNET_SUPPORT->value => SupportsStrategy::class,
        SupportType::IPTV_SUPPORT->value => SupportsStrategy::class,
        SupportType::CHANGE_ADDRESS->value => ChangeAddressStrategy::class,
        SupportType::INTERNET_RENEWAL->value => RenovationStrategy::class,
        SupportType::IPTV_RENEWAL->value => RenovationStrategy::class,
        SupportType::UNINSTALLATION->value => UninstallationStrategy::class,
        SupportType::EQUIPMENT_SALE->value => SupportsStrategy::class,
    ];

    public static function make(int $type): ProcessSupportInterface
    {
        if (!isset(self::$map[$type])) {
            throw new \InvalidArgumentException("Tipo de soporte no disponible");
        }

        return app(self::$map[$type]);
    }
}
