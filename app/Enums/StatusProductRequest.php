<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusProductRequest: string
{
    case WAITING_FOR_RESPONSE = 'waiting_for_response';
    case IN_PRODUCTION = 'in_production';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function ordered(): array
    {
        return [
            self::WAITING_FOR_RESPONSE,
            self::IN_PRODUCTION,
            self::APPROVED,
            self::REJECTED,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::WAITING_FOR_RESPONSE => 'Waiting For Response',
            self::IN_PRODUCTION => 'In Production',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            default => 'Unknown',
        };
    }

    public function getBadgeClass()
    {
        return match ($this) {
            self::WAITING_FOR_RESPONSE => 'bg-secondary',
            self::IN_PRODUCTION => 'bg-warning',
            self::APPROVED => 'bg-primary',
            self::REJECTED => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getTextColor($badgeClass)
    {
        return match ($badgeClass) {
            'bg-secondary' => '#6c757d',
            'bg-warning' => '#ffc107',
            'bg-success' => '#28a745',
            'bg-info' => '#17a2b8',
            'bg-primary' => '#007bff',
            'bg-danger' => '#dc3545',
            default => '#212529',
        };
    }
}
