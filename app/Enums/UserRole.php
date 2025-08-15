<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case ADMINISTRATOR = 'administrator';
    case SALES = 'sales';
    case INVENTORY = 'inventory';
    case PRODUCTION = 'production';
    case TESTING = 'testing';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMINISTRATOR => 'Administrator',
            self::SALES => 'Sales',
            self::INVENTORY => 'Inventory',
            self::PRODUCTION => 'Production',
            self::TESTING => 'Testing',
            default => 'Unknown',
        };
    }

    public function getBadgeClass()
    {
        return match ($this) {
            self::ADMINISTRATOR => 'bg-primary',
            self::SALES => 'bg-warning',
            self::INVENTORY => 'bg-info',
            self::PRODUCTION => 'bg-danger',
            self::TESTING => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
