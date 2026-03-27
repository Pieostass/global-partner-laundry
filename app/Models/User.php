<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ── Fillable ──────────────────────────────────────────────────────────────
    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'address',
        'role',
        'active',
    ];

    // ── Hidden (never serialised to JSON / arrays) ────────────────────────────
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ── Casts ─────────────────────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            // ❌ DO NOT use 'hashed' cast — it auto-hashes on every assignment.
            // Combined with explicit Hash::make() in services/seeders it would
            // double-hash the password, making Auth::attempt() always fail.
            // Password hashing is handled explicitly via Hash::make() where needed.
            'role'   => Role::class,  // maps to App\Enums\Role backed enum
            'active' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * hasMany Order
     * Java: @OneToMany(mappedBy = "user", cascade = CascadeType.ALL)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderByDesc('created_at');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Matches Java Role.ROLE_ADMIN check used in SecurityConfig */
    public function isAdmin(): bool
    {
        return $this->role === Role::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === Role::ROLE_STAFF;
    }

    public function isUser(): bool
    {
        return $this->role === Role::ROLE_USER;
    }

    /**
     * Override the login guard check to respect the 'active' column
     * (Java: .disabled(!user.isEnabled()) in loadUserByUsername)
     */
    public function isActive(): bool
    {
        return (bool) $this->active;
    }
}
