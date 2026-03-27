<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\SiteConfigService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mirrors Java AdminController — @RequestMapping("/admin") @PreAuthorize("hasRole('ADMIN')")
 * Access is enforced by Route::middleware(['auth', 'role:ROLE_ADMIN']) in web.php
 */
class AdminController extends Controller
{
    public function __construct(
        private ProductService    $productService,
        private OrderService      $orderService,
        private UserService       $userService,
        private SiteConfigService $siteConfigService,
    ) {}

    // ══════════════════════════════════════════════════════════════════════════
    // DASHBOARD
    // Java: @GetMapping("/dashboard")
    // ══════════════════════════════════════════════════════════════════════════

    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'totalProducts' => $this->productService->countActive(),
            'totalUsers'    => $this->userService->findAll()->count(),
            'totalRevenue'  => $this->orderService->totalRevenue(),
            'totalOrders'   => $this->orderService->findAll()->count(),
            'pendingOrders' => $this->orderService->countByStatus(OrderStatus::PENDING),
            'recentOrders'  => $this->orderService->findAllPaged(5)->items(),
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PRODUCTS
    // ══════════════════════════════════════════════════════════════════════════

    /** Java: @GetMapping("/products") */
    public function products(Request $request): View
    {
        $keyword    = $request->input('keyword');
        $categoryId = $request->input('categoryId');

        return view('admin.products', [
            'products'   => $this->productService->searchProducts($keyword, $categoryId, 10),
            'categories' => Category::orderBy('name')->get(),
            'keyword'    => $keyword,
            'categoryId' => $categoryId,
        ]);
    }

    /** Java: @GetMapping("/products/new") */
    public function createProduct(): View
    {
        return view('admin.product-form', [
            'product'    => null,       // null = create mode
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /** Java: @PostMapping("/products/new") */
    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        try {
            $this->productService->save($validated, $request->file('image'));
            return redirect()->route('admin.products')
                ->with('success', 'Tạo sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /** Java: @GetMapping("/products/edit/{id}") */
    public function editProduct(int $id): View
    {
        return view('admin.product-form', [
            'product'    => $this->productService->findById($id),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /** Java: @PostMapping("/products/edit/{id}") */
    public function updateProduct(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        try {
            $this->productService->update($id, $validated, $request->file('image'));
            return redirect()->route('admin.products')
                ->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /** Java: @PostMapping("/products/delete/{id}") — soft delete */
    public function deleteProduct(int $id): RedirectResponse
    {
        $this->productService->deactivate($id);
        return redirect()->route('admin.products')
            ->with('success', 'Đã vô hiệu hóa sản phẩm.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ORDERS
    // ══════════════════════════════════════════════════════════════════════════

    /** Java: @GetMapping("/orders") */
    public function orders(Request $request): View
    {
        $status = $request->input('status');

        return view('admin.orders', [
            'orders'        => $this->orderService->findAllPaged(15, $status ?: null),
            'statuses'      => OrderStatus::cases(),
            'currentStatus' => $status,
        ]);
    }

    /** Java: @GetMapping("/orders/{id}") */
   public function orderDetail(int $id): View
{
    return view('admin.order-detail', [
        'order' => $this->orderService->findById($id),
        'statuses' => OrderStatus::cases(),
    ]);
}

    /** Java: @PostMapping("/orders/{id}/status") */
    public function updateOrderStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate(['status' => ['required', 'string']]);

        $this->orderService->updateStatus($id, $request->input('status'));

        return redirect()->route('admin.orders')
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // USERS
    // ══════════════════════════════════════════════════════════════════════════

    /** Java: @GetMapping("/users") */
    public function users(): View
    {
        return view('admin.users', [
            'users' => $this->userService->findAll(),
        ]);
    }

    /** Java: @PostMapping("/users/{id}/toggle") */
    public function toggleUser(int $id): RedirectResponse
    {
        $this->userService->toggleUserEnabled($id);
        return redirect()->route('admin.users')
            ->with('success', 'Cập nhật trạng thái người dùng thành công!');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // SITE SETTINGS
    // ══════════════════════════════════════════════════════════════════════════

    /** Java: @GetMapping("/settings") */
 public function settings(): View
{
    return view('admin.settings', [
        'configs' => $this->siteConfigService->findAll(),
    ]);
}

    /** Java: @PostMapping("/settings") */
public function saveSettings(Request $request): RedirectResponse
{
    $data = $request->except('_token', '_method');

    // Xử lý upload logo
    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('logos', 'public');
        $data['logo'] = $path;
    }

    $this->siteConfigService->saveAll($data);

    return redirect()->route('admin.settings')
        ->with('success', 'Đã lưu cài đặt giao diện thành công!');
}

    // ── Private Helpers ───────────────────────────────────────────────────────

    /** Shared validation rules — mirrors Java @Valid ProductDto annotations */
    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name'           => ['required', 'string', 'max:200'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'price'          => ['required', 'numeric', 'min:0.01'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id'    => ['required', 'exists:categories,id'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_url'      => ['nullable', 'string'],
        ], [
            'name.required'        => 'Tên sản phẩm là bắt buộc.',
            'price.required'       => 'Giá sản phẩm là bắt buộc.',
            'price.min'            => 'Giá phải lớn hơn 0.',
            'stock_quantity.min'   => 'Số lượng không được âm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists'   => 'Danh mục không tồn tại.',
            'image.image'          => 'File tải lên phải là hình ảnh.',
            'image.max'            => 'Ảnh không được vượt quá 2MB.',
        ]);
    }
}
