<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mirrors Java UserController — shop, product detail, orders, profile
 */
class UserController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private OrderService   $orderService,
        private UserService    $userService,
    ) {}

    // ── GET /shop ─────────────────────────────────────────────────────────────
    /** Java: @GetMapping("/shop") */
    public function shop(Request $request): View
    {
        $keyword    = $request->input('keyword');
        $categoryId = $request->input('categoryId') ? (int) $request->input('categoryId') : null;
        $perPage    = (int) $request->input('size', 12);

        $products = match (true) {
            (bool) $keyword    => $this->productService->searchByName($keyword, $perPage),
            (bool) $categoryId => $this->productService->findByCategoryIdPaged($categoryId, $perPage),
            default            => $this->productService->findAllActivePaged($perPage),
        };

        return view('user.shop', [
            'products'         => $products,
            'categories'       => $this->productService->findAllCategories(),
            'keyword'          => $keyword,
            'categoryId'       => $categoryId,
            'selectedCategory' => $categoryId,   // shop.blade.php uses selectedCategory
        ]);
    }

    // ── GET /product/{id} ─────────────────────────────────────────────────────
    /** Java: @GetMapping("/product/{id}") */
    public function productDetail(int $id): View
    {
        $product = $this->productService->findById($id);

        // Java: products.stream().filter(p -> active && !id && same category).limit(6)
        $related = $this->productService->findAll()
            ->filter(fn($p) =>
                $p->active &&
                $p->id !== $id &&
                $p->category_id !== null &&
                $p->category_id === $product->category_id
            )
            ->take(6)
            ->values();

        return view('user.product-detail', [
            'product'        => $product,
            'relatedProducts'=> $related,
        ]);
    }

    // ── GET /order/success ────────────────────────────────────────────────────
    /** Java: @GetMapping("/order/success") */
    public function orderSuccess(Request $request): View
    {
        $id = (int) $request->input('id');
        return view('user.order-success', [
            'orderId' => $id,
            'order'   => $this->orderService->findById($id),
        ]);
    }

    // ── GET /order/history ────────────────────────────────────────────────────
    /** Java: @GetMapping("/order/history") */
    public function orderHistory(): View|RedirectResponse
    {
        return view('user.order-history', [
            'orders' => $this->orderService->findByUsername(auth()->user()->username),
        ]);
    }

    // ── GET /user/orders ──────────────────────────────────────────────────────
    /** Java: @GetMapping("/user/orders") — alias used in layout navbar */
    public function userOrders(): View
    {
        return view('user.order-history', [
            'orders' => $this->orderService->findByUsername(auth()->user()->username),
        ]);
    }

    // ── GET /user/profile ─────────────────────────────────────────────────────
    /** Java: @GetMapping("/user/profile") */
    public function profile(): View
    {
        return view('user.profile', [
            'user' => auth()->user(),
        ]);
    }

    // ── POST /user/profile ────────────────────────────────────────────────────
    /** Not in original Java but needed for profile edit form submission */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'max:150'],
            'phone'     => ['nullable', 'string', 'regex:/^(\+84|0)[0-9]{9,10}$/'],
            'address'   => ['nullable', 'string', 'max:500'],
        ]);

        $this->userService->updateProfile(auth()->user()->username, $validated);

        return redirect()->route('profile')
            ->with('success', 'Cập nhật thông tin thành công!');
    }
}
