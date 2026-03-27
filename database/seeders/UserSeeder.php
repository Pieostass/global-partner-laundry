<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Mirrors Java DataInitializer — seeds admin1, staff1, user1
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username'  => 'admin1',
                'email'     => 'admin@laundry.vn',
                'password'  => Hash::make('Admin@123'),
                'full_name' => 'Administrator',
                'role'      => Role::ROLE_ADMIN,
                'active'    => true,
            ],
            [
                'username'  => 'staff1',
                'email'     => 'staff@laundry.vn',
                'password'  => Hash::make('Staff@123'),
                'full_name' => 'Nhân viên 1',
                'role'      => Role::ROLE_STAFF,
                'active'    => true,
            ],
            [
                'username'  => 'user1',
                'email'     => 'user@laundry.vn',
                'password'  => Hash::make('User@1234'),
                'full_name' => 'Khách hàng 1',
                'role'      => Role::ROLE_USER,
                'active'    => true,
            ],
        ];

        foreach ($users as $data) {
            // Mirrors Java: if (!userRepository.existsByUsername("admin1"))
            User::firstOrCreate(
                ['username' => $data['username']],
                $data
            );
        }
    }
}
