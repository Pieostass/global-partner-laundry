<?php

namespace App\Enums;

/**
 * Mirrors Java: com.laundryshop.enums.Role
 * Stored as string in DB column 'role'.
 */
enum Role: string
{
    case ROLE_USER  = 'ROLE_USER';
    case ROLE_STAFF = 'ROLE_STAFF';
    case ROLE_ADMIN = 'ROLE_ADMIN';

    /** Human-readable label used in Blade templates */
    public function label(): string
    {
        return match ($this) {
            Role::ROLE_USER  => 'Khách hàng',
            Role::ROLE_STAFF => 'Nhân viên',
            Role::ROLE_ADMIN => 'Quản trị viên',
        };
    }

    /** Tailwind badge class for admin user-list table */
    public function badgeClass(): string
    {
        return match ($this) {
            Role::ROLE_USER  => 'bg-gray-100 text-gray-700',
            Role::ROLE_STAFF => 'bg-blue-100 text-blue-700',
            Role::ROLE_ADMIN => 'bg-red-100 text-red-700',
        };
    }
}
