<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusProduction: string
{
    case WAITING_FOR_RESPONSE = 'waiting_for_response';
    case IN_PROGRESS = 'in_progress';
    case COMPLETE = 'complete';
    case PENDING_APPROVAL = 'pending_approval';
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
            self::IN_PROGRESS,
            self::COMPLETE,
            self::PENDING_APPROVAL,
            self::APPROVED,
            self::REJECTED,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::WAITING_FOR_RESPONSE => 'Waiting for Response',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETE => 'Complete',
            self::PENDING_APPROVAL => 'Pending Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            default => 'Unknown',
        };
    }

    public function getBadgeClass()
    {
        return match ($this) {
            self::WAITING_FOR_RESPONSE => 'bg-secondary',
            self::IN_PROGRESS => 'bg-warning',
            self::COMPLETE => 'bg-success',
            self::PENDING_APPROVAL => 'bg-info',
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
