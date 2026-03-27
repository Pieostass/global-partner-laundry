<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mirrors Java HomeController — public-facing pages
 */
class HomeController extends Controller
{
    public function __construct(private ProductService $productService) {}

    // ── GET / ─────────────────────────────────────────────────────────────────
    /** Java: @GetMapping("/") */
    public function index(): View
    {
        $featured = $this->productService->findAll()
            ->filter(fn($p) => $p->active)
            ->take(4)
            ->values();

        return view('user.home', [
            'featuredProducts' => $featured,
            'featuredProduct'  => $featured->first(), // Java: featured.isEmpty() ? null : featured.get(0)
            // $siteConfig is shared globally via View::composer in AppServiceProvider
        ]);
    }

    // ── GET /flash-sale ───────────────────────────────────────────────────────
    /** Java: @GetMapping("/flash-sale") */
    public function flashSale(): View
    {
        $saleProducts = $this->productService->findAll()
            ->filter(fn($p) => $p->active)
            ->take(8)
            ->values();

        return view('user.flash-sale', [
            'saleProducts' => $saleProducts,
            'products'     => $saleProducts,   // flash-sale.blade.php uses $products
            'categories'   => $this->productService->findAllCategories(),
        ]);
    }

    // ── POST /contact ─────────────────────────────────────────────────────────
    /** Java: @PostMapping("/contact") */
    public function contact(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string'],
        ]);

        // Java: TODO — save to DB or send email
        // For now, flash a success message
        return redirect()->route('home')
            ->with('contactSuccess', 'Cảm ơn ' . $request->input('name') . '! Chúng tôi sẽ liên hệ lại sớm nhất.');
    }
}
