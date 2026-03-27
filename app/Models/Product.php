<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
        'active',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price'         => 'decimal:2',
            'active'        => 'boolean',
            'stock_quantity'=> 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * belongsTo Category
     * Java: @ManyToOne(fetch = FetchType.EAGER) @JoinColumn(name = "category_id")
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * hasMany OrderItem
     * Java: product is referenced in OrderItem with @ManyToOne
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }
        return $query;
    }

    public function scopeByCategory($query, ?int $categoryId)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        return $query;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Returns the public image URL.
     * If image_url starts with 'http' it's an external URL; otherwise serve from storage.
     */
    public function getImageSrcAttribute(): string
    {
        if (!$this->image_url) {
            return asset('images/product-placeholder.png');
        }

        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        return asset('storage/' . $this->image_url);
    }
    
}
