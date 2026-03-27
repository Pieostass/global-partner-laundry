<?php

namespace App\Enums;

/**
 * Mirrors Java: com.laundryshop.enums.OrderStatus
 * Stored as string in DB column 'status'.
 */
enum OrderStatus: string
{
    case PENDING    = 'PENDING';
    case CONFIRMED  = 'CONFIRMED';
    case PROCESSING = 'PROCESSING';
    case DELIVERING = 'DELIVERING';
    case DELIVERED  = 'DELIVERED';
    case DONE       = 'DONE';
    case CANCELLED  = 'CANCELLED';

    /** Mirrors Java getLabel() */
    public function label(): string
    {
        return match ($this) {
            OrderStatus::PENDING    => 'Chờ xác nhận',
            OrderStatus::CONFIRMED  => 'Đã xác nhận',
            OrderStatus::PROCESSING => 'Đang xử lý',
            OrderStatus::DELIVERING => 'Đang giao',
            OrderStatus::DELIVERED  => 'Đã giao',
            OrderStatus::DONE       => 'Hoàn thành',
            OrderStatus::CANCELLED  => 'Đã hủy',
        };
    }

    /** Tailwind CSS classes — mirrors Java getBadgeClass() */
    public function badgeClass(): string
    {
        return match ($this) {
            OrderStatus::PENDING    => 'bg-yellow-100 text-yellow-800',
            OrderStatus::CONFIRMED  => 'bg-blue-100 text-blue-800',
            OrderStatus::PROCESSING => 'bg-indigo-100 text-indigo-800',
            OrderStatus::DELIVERING => 'bg-purple-100 text-purple-800',
            OrderStatus::DELIVERED  => 'bg-green-100 text-green-800',
            OrderStatus::DONE       => 'bg-green-200 text-green-900',
            OrderStatus::CANCELLED  => 'bg-red-100 text-red-800',
        };
    }

    /** Active (in-progress) statuses for Staff dashboard filter */
    public static function activeStatuses(): array
    {
        return [self::PROCESSING, self::DELIVERING];
    }

    /** Statuses that should NOT appear on the delivery board */
    public static function closedStatuses(): array
    {
        return [self::DONE, self::CANCELLED];
    }
}
