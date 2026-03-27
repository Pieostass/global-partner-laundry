<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Mirrors Java UserServiceImpl.
 * Note: Laravel's Auth handles loadUserByUsername automatically — we only
 * need the business-logic methods here.
 */
class UserService
{
    // ── register ──────────────────────────────────────────────────────────────
    /**
     * Java: User register(RegisterDto dto)
     * $data mirrors RegisterDto fields: username, email, password, full_name, phone
     */
    public function register(array $data): User
    {
        // Java: if (userRepository.existsByUsername(...)) throw RuntimeException
        if (User::where('username', $data['username'])->exists()) {
            throw ValidationException::withMessages([
                'username' => 'Tên đăng nhập đã tồn tại!',
            ]);
        }

        // Java: if (userRepository.existsByEmail(...)) throw RuntimeException
        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email đã được sử dụng!',
            ]);
        }

        return User::create([
            'username'  => $data['username'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'full_name' => $data['full_name'],
            'phone'     => $data['phone'] ?? null,
            'role'      => Role::ROLE_USER,
            'active'    => true,
        ]);
    }

    // ── findByUsername ────────────────────────────────────────────────────────
    /** Java: User findByUsername(String username) */
    public function findByUsername(string $username): User
    {
        return User::where('username', $username)->firstOrFail();
    }

    // ── findAll ───────────────────────────────────────────────────────────────
    /** Java: List<User> findAll() */
    public function findAll(): Collection
    {
        return User::orderByDesc('created_at')->get();
    }

    // ── toggleUserEnabled ─────────────────────────────────────────────────────
    /** Java: void toggleUserEnabled(Long userId) */
    public function toggleUserEnabled(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['active' => !$user->active]);
    }

    // ── updateProfile ─────────────────────────────────────────────────────────
    /** Java: User updateProfile(String username, User updatedData) */
    public function updateProfile(string $username, array $data): User
    {
        $user = $this->findByUsername($username);

        $user->update(array_filter([
            'full_name' => $data['full_name'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'email'     => $data['email'] ?? null,
            'address'   => $data['address'] ?? null,
        ], fn($v) => $v !== null));

        return $user->fresh();
    }

    // ── changePassword ────────────────────────────────────────────────────────
    /** Not in Java original but needed by Breeze's PasswordController */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->update(['password' => Hash::make($newPassword)]);
    }
}
