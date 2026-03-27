<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Mirrors Java ProductServiceImpl.
 * All public methods map 1-to-1 with the Java interface methods.
 */
class ProductService
{
    // ── findAll ───────────────────────────────────────────────────────────────
    /** Java: List<Product> findAll() */
    public function findAll(): Collection
    {
        return Product::with('category')->get();
    }

    // ── findById ──────────────────────────────────────────────────────────────
    /** Java: Product findById(Long id) — throws RuntimeException if not found */
    public function findById(int $id): Product
    {
        return Product::with('category')->findOrFail($id);
    }

    // ── findAllActivePaged ────────────────────────────────────────────────────
    /** Java: Page<Product> findAllActivePaged(Pageable pageable) */
    public function findAllActivePaged(int $perPage = 12): LengthAwarePaginator
    {
        return Product::with('category')
            ->active()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    // ── findByCategoryIdPaged ─────────────────────────────────────────────────
    /** Java: Page<Product> findByCategoryIdPaged(Long categoryId, Pageable pageable) */
    public function findByCategoryIdPaged(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        return Product::with('category')
            ->active()
            ->where('category_id', $categoryId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    // ── searchByName ──────────────────────────────────────────────────────────
    /** Java: Page<Product> searchByName(String keyword, Pageable pageable) */
    public function searchByName(string $keyword, int $perPage = 12): LengthAwarePaginator
    {
        return Product::with('category')
            ->active()
            ->where('name', 'like', "%{$keyword}%")
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    // ── searchProducts (admin) ────────────────────────────────────────────────
    /**
     * Java: Page<Product> searchProducts(String keyword, Long categoryId, Pageable pageable)
     * Used in AdminController — includes inactive products for management view.
     */
    public function searchProducts(?string $keyword, ?int $categoryId, int $perPage = 10): LengthAwarePaginator
    {
        return Product::with('category')
            ->when($keyword, fn($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    // ── findAllCategories ─────────────────────────────────────────────────────
    /** Java: List<Category> findAllCategories() */
    public function findAllCategories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    // ── countActive ───────────────────────────────────────────────────────────
    /** Java: long countActive() */
    public function countActive(): int
    {
        return Product::where('active', true)->count();
    }

    // ── save (create from DTO) ────────────────────────────────────────────────
    /**
     * Java: Product save(ProductDto dto)
     * Accepts an array mirroring ProductDto fields + optional UploadedFile.
     */
    public function save(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['image_url'] = $this->storeImage($image);
        }

        return Product::create([
            'name'           => $data['name'],
            'description'    => $data['description'] ?? null,
            'price'          => $data['price'],
            'stock_quantity' => $data['stock_quantity'],
            'image_url'      => $data['image_url'] ?? null,
            'category_id'    => $data['category_id'],
            'active'         => true,
        ]);
    }

    // ── update ────────────────────────────────────────────────────────────────
    /** Java: Product update(Long id, ProductDto dto) */
    public function update(int $id, array $data, ?UploadedFile $image = null): Product
    {
        $product = $this->findById($id);

        if ($image) {
            // Delete old image from storage before replacing
            if ($product->image_url && !str_starts_with($product->image_url, 'http')) {
                Storage::disk('public')->delete($product->image_url);
            }
            $data['image_url'] = $this->storeImage($image);
        }

        $product->update([
            'name'           => $data['name'],
            'description'    => $data['description'] ?? null,
            'price'          => $data['price'],
            'stock_quantity' => $data['stock_quantity'],
            'image_url'      => $data['image_url'] ?? $product->image_url,
            'category_id'    => $data['category_id'],
        ]);

        return $product->fresh('category');
    }

    // ── deactivate ────────────────────────────────────────────────────────────
    /** Java: void deactivate(Long id) — soft delete by setting active=false */
    public function deactivate(int $id): void
    {
        $this->findById($id)->update(['active' => false]);
    }

    // ── delete ────────────────────────────────────────────────────────────────
    /** Java: void delete(Long id) — hard delete (used in tests/cleanup only) */
    public function delete(int $id): void
    {
        $product = $this->findById($id);
        if ($product->image_url && !str_starts_with($product->image_url, 'http')) {
            Storage::disk('public')->delete($product->image_url);
        }
        $product->delete();
    }

    // ── FileStorageService equivalent ─────────────────────────────────────────
    /**
     * Mirrors Java FileStorageService::store()
     * Saves to storage/app/public/products — accessible via public/storage/products/
     * Run: php artisan storage:link  (once after deploy)
     */
    private function storeImage(UploadedFile $file): string
    {
        // Returns path like: products/filename.jpg
        return $file->store('products', 'public');
    }
}
