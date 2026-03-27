<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mirrors Java CartController.
 * Cart is stored in Laravel session as: ['productId' => [...product data + qty]]
 * Key: 'cart'  —  mirrors Java CART_SESSION_KEY = "cart"
 */
class CartController extends Controller
{
    private const CART_KEY = 'cart';

    public function __construct(
        private ProductService $productService,
        private OrderService   $orderService,
    ) {}

    // ── GET /cart ─────────────────────────────────────────────────────────────
    /** Java: @GetMapping("/cart") */
    public function viewCart(Request $request): View
    {
        $cart  = $this->getCart($request);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('user.cart', [
            'cartItems' => collect($cart)->values(),
            'total'     => $total,
        ]);
    }

    // ── POST /cart/add ────────────────────────────────────────────────────────
    /** Java: @PostMapping("/cart/add") */
    public function addToCart(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = (int) $request->input('product_id');
        $quantity  = (int) ($request->input('quantity', 1));

        try {
            $product = $this->productService->findById($productId);
            $cart    = $this->getCart($request);

            if (isset($cart[$productId])) {
                // Java: cart.merge(productId, ..., (existing, newItem) -> existing.qty + qty)
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'product_id'   => $productId,
                    'product_name' => $product->name,
                    'price'        => (float) $product->price,
                    'quantity'     => $quantity,
                    'image_url'    => $product->image_url,
                    'image_src'    => $product->image_src,
                ];
            }

            $request->session()->put(self::CART_KEY, $cart);

            return redirect()->route('cart')
                ->with('success', "Đã thêm '{$product->name}' vào giỏ hàng!");
        } catch (\Exception $e) {
            return redirect()->route('cart')
                ->with('error', $e->getMessage());
        }
    }

    // ── POST /cart/update ─────────────────────────────────────────────────────
    /** Java: @PostMapping("/cart/update") */
    public function updateCart(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity'   => ['required', 'integer'],
        ]);

        $productId = (int) $request->input('product_id');
        $quantity  = (int) $request->input('quantity');
        $cart      = $this->getCart($request);

        if ($quantity <= 0) {
            unset($cart[$productId]);
            $request->session()->put(self::CART_KEY, $cart);
            return redirect()->route('cart')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $request->session()->put(self::CART_KEY, $cart);
        }

        return redirect()->route('cart')->with('success', 'Đã cập nhật số lượng');
    }

    // ── POST /cart/remove ─────────────────────────────────────────────────────
    /** Java: @PostMapping("/cart/remove") */
    public function removeFromCart(Request $request): RedirectResponse
    {
        $productId = (int) $request->input('product_id');
        $cart      = $this->getCart($request);

        unset($cart[$productId]);
        $request->session()->put(self::CART_KEY, $cart);

        return redirect()->route('cart')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    // ── GET /cart/clear ───────────────────────────────────────────────────────
    /** Java: @GetMapping("/cart/clear") */
    public function clearCart(Request $request): RedirectResponse
    {
        $request->session()->forget(self::CART_KEY);
        return redirect()->route('cart')->with('success', 'Đã xóa toàn bộ giỏ hàng');
    }

    // ── GET /cart/count (AJAX) ────────────────────────────────────────────────
    /** Java: @GetMapping("/cart/count") @ResponseBody */
    public function cartCount(Request $request): JsonResponse
    {
        $count = collect($this->getCart($request))->sum('quantity');
        return response()->json(['count' => $count]);
    }

    // ── GET /checkout ─────────────────────────────────────────────────────────
    /** Java: @GetMapping("/checkout") */
   public function checkoutPage(Request $request): View|RedirectResponse
{
    $cart = $this->getCart($request);
    if (empty($cart)) {
        return redirect()->route('cart');
    }

    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

    // Lấy thông tin user nếu có, nếu không thì mảng rỗng
    $userInfo = auth()->check() ? auth()->user() : null;

    return view('user.checkout', [
        'cartItems' => collect($cart)->values(),
        'total'     => $total,
        'userInfo'  => $userInfo,
    ]);
}

    // ── POST /checkout ────────────────────────────────────────────────────────
    /** Java: @PostMapping("/checkout") */
public function placeOrder(Request $request): View|RedirectResponse
{
    $validated = $request->validate([
        'full_name' => ['required', 'string', 'max:150'],
        'address'   => ['required', 'string', 'max:500'],
        'phone'     => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
        'note'      => ['nullable', 'string', 'max:500'],
    ]);

    $cart = $this->getCart($request);

    if (empty($cart)) {
        return redirect()->route('cart');
    }

    try {
        $cartSimple = collect($cart)->mapWithKeys(fn($item, $id) => [$id => $item['quantity']])->toArray();

        // Lấy username nếu có, nếu không thì null
        $username = auth()->user() ? auth()->user()->username : null;

        $orderId = $this->orderService->placeOrder($username, $cartSimple, $validated);

        $request->session()->forget(self::CART_KEY);

        // Thông báo thành công
        $message = 'Đặt hàng thành công! Cảm ơn bạn đã mua hàng 🎉';

        if (auth()->check()) {
            return redirect()->route('user.orders')->with('success', $message);
        } else {
            return redirect()->route('home')->with('success', $message);
        }
    } catch (\Exception $e) {
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('user.checkout', [
            'cartItems' => collect($cart)->values(),
            'total'     => $total,
            'error'     => $e->getMessage(),
        ]);
    }
}

    // ── Helper ────────────────────────────────────────────────────────────────
    /** Java: private Map<Long, CartItem> getCart(HttpSession session) */
    private function getCart(Request $request): array
    {
        return $request->session()->get(self::CART_KEY, []);
    }
}
