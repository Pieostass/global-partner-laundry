<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'note',
        'status',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'status'      => OrderStatus::class,   // backed enum
            'total_price' => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * belongsTo User
     * Java: @ManyToOne(fetch = FetchType.LAZY) @JoinColumn(name = "user_id")
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * hasMany OrderItem  (cascade delete is handled at DB level)
     * Java: @OneToMany(mappedBy="order", cascade=ALL, orphanRemoval=true)
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeByStatus($query, ?string $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Convenience: badge Tailwind class for order status */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            OrderStatus::PENDING    => 'bg-yellow-100 text-yellow-800',
            OrderStatus::CONFIRMED  => 'bg-blue-100 text-blue-800',
            OrderStatus::PROCESSING => 'bg-indigo-100 text-indigo-800',
            OrderStatus::DELIVERING => 'bg-purple-100 text-purple-800',
            OrderStatus::DELIVERED  => 'bg-green-100 text-green-800',
            OrderStatus::DONE       => 'bg-green-200 text-green-900',
            OrderStatus::CANCELLED  => 'bg-red-100 text-red-800',
            default                 => 'bg-gray-100 text-gray-800',
        };
    }

    /** Human-readable Vietnamese label — mirrors Java OrderStatus::getLabel() */
    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? $this->getRawOriginal('status');
    }
}
