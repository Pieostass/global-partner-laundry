<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * hasMany Product
     * Java: @OneToMany(mappedBy = "category", fetch = FetchType.LAZY)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /** Scope to only return active categories */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
