<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price'    => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * belongsTo Order
     * Java: @ManyToOne(fetch = FetchType.LAZY) @JoinColumn(name = "order_id")
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * belongsTo Product
     * Java: @ManyToOne(fetch = FetchType.EAGER) @JoinColumn(name = "product_id")
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name'      => 'Sản phẩm đã bị xoá',
            'price'     => 0,
            'image_url' => null,
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Mirrors Java: public BigDecimal getSubTotal()
     * price * quantity — uses the snapshot price, not current product price
     */
    public function getSubTotalAttribute(): string
    {
        return number_format((float) $this->price * $this->quantity, 2, '.', '');
    }
}
