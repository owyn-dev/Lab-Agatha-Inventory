<?php

declare(strict_types=1);

namespace App\Enums;

enum VariantProduct: string
{
    case TABUNG_S = 'tabung_s';
    case TABUNG_M = 'tabung_m';
    case KOTAK = 'kotak';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getNameByValue(string $value): string
    {
        return match ($value) {
            self::TABUNG_S->value => 'Tabung S',
            self::TABUNG_M->value => 'Tabung M',
            self::KOTAK->value => 'Kotak',
            default => 'Unknown',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::TABUNG_S => 'Tabung S',
            self::TABUNG_M => 'Tabung M',
            self::KOTAK => 'Kotak',
            default => 'Unknown',
        };
    }
}
