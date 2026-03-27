<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Mirrors Java OrderServiceImpl — all method signatures preserved.
 */
class OrderService
{
    // ── placeOrder (from session cart + CheckoutDto) ──────────────────────────
    /**
     * Java: Long placeOrder(String username, Map<Long,Integer> cartItems, CheckoutDto dto)
     * $cart = ['product_id' => quantity, ...]
     * $dto  = ['address' => '', 'phone' => '', 'note' => '']
     */
   public function placeOrder(?string $username, array $cart, array $dto): int
{
    if (empty($cart)) {
        throw new \RuntimeException('Giỏ hàng trống');
    }

    $userId = null;
    if ($username) {
        $user = User::where('username', $username)->firstOrFail();
        $userId = $user->id;
    }

    return DB::transaction(function () use ($userId, $cart, $dto) {
        $order = Order::create([
            'user_id'     => $userId,
            'full_name'   => $dto['full_name'] ?? null,
            'phone'       => $dto['phone'],
            'address'     => $dto['address'],
            'note'        => $dto['note'] ?? null,
            'status'      => OrderStatus::PENDING,
            'total_price' => 0,
        ]);

        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::findOrFail($productId);
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price,
            ]);
            $total += $product->price * $quantity;
        }

        $order->update(['total_price' => $total]);

        return $order->id;
    });
}

public function placeOrderGuest(string $fullName, ?string $email, string $phone, string $address, ?string $note, array $cart): int
{
    if (empty($cart)) {
        throw new \RuntimeException('Giỏ hàng trống');
    }

    return DB::transaction(function () use ($fullName, $email, $phone, $address, $note, $cart) {
        // Tạo order với user_id = null, nhưng lưu đầy đủ thông tin
        $order = Order::create([
            'user_id'     => null,
            'full_name'   => $fullName,
            'email'       => $email,
            'phone'       => $phone,
            'address'     => $address,
            'note'        => $note,
            'status'      => OrderStatus::PENDING,
            'total_price' => 0,
        ]);

        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::findOrFail($productId);
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price,
            ]);
            $total += $product->price * $quantity;
        }

        $order->update(['total_price' => $total]);
        return $order->id;
    });
}

    // ── placeOrderFromProfile (mirrors first overload in Java) ─────────────────
    /**
     * Java: Long placeOrder(String username, String fullName, String phone, String address, String note)
     * Uses cart from CartService (session) — kept for compatibility with UserController.
     */
    public function placeOrderFromProfile(
        string $username,
        string $fullName,
        string $phone,
        string $address,
        ?string $note,
        array $cart
    ): int {
        return $this->placeOrder($username, $cart, [
            'phone'   => $phone,
            'address' => $address,
            'note'    => $note,
        ]);
    }

    // ── findByUsername ────────────────────────────────────────────────────────
    /** Java: List<Order> findByUsername(String username) */
    public function findByUsername(string $username): Collection
    {
        return Order::with(['orderItems.product'])
            ->whereHas('user', fn($q) => $q->where('username', $username))
            ->orderByDesc('created_at')
            ->get();
    }

    // ── findById ──────────────────────────────────────────────────────────────
    /** Java: Order findById(Long id) */
    public function findById(int $id): Order
    {
        return Order::with(['orderItems.product', 'user'])->findOrFail($id);
    }

    // ── findAll (list) ────────────────────────────────────────────────────────
    /** Java: List<Order> findAll() */
    public function findAll(?string $status = null): Collection
    {
        return Order::with(['user', 'orderItems'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->get();
    }

    // ── findAll (paginated) ───────────────────────────────────────────────────
    /** Java: Page<Order> findAll(Pageable pageable) */
    public function findAllPaged(int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        return Order::with(['user', 'orderItems'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    // ── updateStatus ──────────────────────────────────────────────────────────
    /** Java: Order updateStatus(Long orderId, String status) */
    public function updateStatus(int $orderId, string|OrderStatus $status): Order
    {
        $order = Order::findOrFail($orderId);

        // Accept both string and enum — mirrors Java's two updateStatus overloads
        $statusValue = $status instanceof OrderStatus ? $status->value : $status;

        $order->update(['status' => $statusValue]);

        return $order->fresh();
    }

    // ── countByStatus ─────────────────────────────────────────────────────────
    /** Java: long countByStatus(OrderStatus status) */
    public function countByStatus(OrderStatus|string $status): int
    {
        $value = $status instanceof OrderStatus ? $status->value : $status;
        return Order::where('status', $value)->count();
    }

    // ── totalRevenue ──────────────────────────────────────────────────────────
    /**
     * Java: BigDecimal totalRevenue()
     * Sums total_price of all non-cancelled orders.
     */
    public function totalRevenue(): float
    {
        return (float) Order::where('status', '!=', OrderStatus::CANCELLED->value)
            ->sum('total_price');
    }
}
