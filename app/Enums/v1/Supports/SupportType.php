<?php

namespace App\Enums\v1\Supports;

enum SupportType: int
{
    case INTERNET_INSTALLATION = 1;
    case IPTV_INSTALLATION = 2;
    case INTERNET_SUPPORT = 3;
    case IPTV_SUPPORT = 4;
    case CHANGE_ADDRESS = 5;
    case INTERNET_RENEWAL = 6;
    case IPTV_RENEWAL = 7;
    case UNINSTALLATION = 8;
    case EQUIPMENT_SALE = 9;

    public function getName(): string
    {
        return match ($this) {
            self::INTERNET_INSTALLATION => 'Instalación de internet',
            self::IPTV_INSTALLATION => 'Instalación de internet + IPTV',
            self::INTERNET_SUPPORT => 'Soporte de internet',
            self::IPTV_SUPPORT => 'Soporte de IPTV',
            self::CHANGE_ADDRESS => 'Cambio de domicilio',
            self::INTERNET_RENEWAL => 'Renovación de internet',
            self::IPTV_RENEWAL => 'Renovación de internet + IPTV',
            self::UNINSTALLATION => 'Desinstalación',
            self::EQUIPMENT_SALE => 'Venta de equipo',
        };
    }

    public function requiresContractDetails(): bool
    {
        return match ($this) {
            self::INTERNET_INSTALLATION,
            self::IPTV_INSTALLATION,
            self::INTERNET_RENEWAL,
            self::IPTV_RENEWAL => true,
            default => false,
        };
    }

    public function requiresService(): bool
    {
        return match ($this) {
            self::INTERNET_SUPPORT,
            self::IPTV_SUPPORT,
            self::CHANGE_ADDRESS,
            self::INTERNET_RENEWAL,
            self::IPTV_RENEWAL,
            self::UNINSTALLATION,
            self::EQUIPMENT_SALE => true,
            default => false,
        };
    }

    public function isInstallation(): bool
    {
        return match ($this) {
            self::INTERNET_INSTALLATION,
            self::IPTV_INSTALLATION => true,
            default => false,
        };
    }

    public function allowsDuplicates(): bool
    {
        return $this === self::EQUIPMENT_SALE;
    }

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function getInstallationTypes(): array
    {
        return [
            self::INTERNET_INSTALLATION->value,
            self::IPTV_INSTALLATION->value,
        ];
    }
}
