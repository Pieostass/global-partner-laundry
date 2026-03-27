{{--
    ┌─────────────────────────────────────────────────────────────────────┐
    │  PRODUCT CARD COMPONENT                                             │
    │  Usage: @include('components.product-card', ['product' => $product])│
    │  Optional props:                                                    │
    │    $showCategory (bool, default true)                               │
    │    $showAddToCart (bool, default true)                              │
    │    $aspectRatio   (string, default 'aspect-[4/5]')                 │
    └─────────────────────────────────────────────────────────────────────┘
--}}

@php
    $showCategory   = $showCategory  ?? true;
    $showAddToCart  = $showAddToCart ?? true;
    $aspectRatio    = $aspectRatio   ?? 'aspect-[4/5]';
@endphp

<article class="product-card group relative flex flex-col" data-reveal data-delay="{{ $delay ?? '0s' }}">

    {{-- ── Image container ───────────────────────────────────────────────── --}}
    <a href="{{ route('product.detail', $product->id) }}"
       class="block overflow-hidden bg-warm-gray relative {{ $aspectRatio }}">

        {{-- Image with zoom on hover ── --}}
        <img src="{{ $product->image_src }}"
             alt="{{ $product->name }}"
             loading="lazy"
             class="absolute inset-0 w-full h-full object-cover
                    transition-transform duration-700 ease-out
                    group-hover:scale-105">

        {{-- Subtle dark vignette on hover ── --}}
        <div class="absolute inset-0 bg-ink/0 group-hover:bg-ink/8 transition-colors duration-500"></div>

        {{-- Stock badge ── --}}
        @if($product->stock_quantity <= 0)
        <div class="absolute top-3 left-3 bg-cream px-2.5 py-1">
            <span class="text-[9px] tracking-widest uppercase text-ink-muted">Hết hàng</span>
        </div>
        @endif

        {{-- Quick-add overlay (appears on hover) ── --}}
        @if($showAddToCart && $product->stock_quantity > 0)
        <div class="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0
                    transition-transform duration-400 ease-out">
            @auth
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity"   value="1">
                <button type="submit"
                        class="w-full bg-ink/90 backdrop-blur-sm text-cream text-[10px] tracking-widest
                               uppercase py-3.5 hover:bg-bronze transition-colors duration-300">
                    Thêm vào giỏ
                </button>
            </form>
            @else
            <a href="{{ route('login') }}"
               class="block w-full text-center bg-ink/90 backdrop-blur-sm text-cream text-[10px]
                      tracking-widest uppercase py-3.5 hover:bg-bronze transition-colors duration-300">
                Mua ngay
            </a>
            @endauth
        </div>
        @endif
    </a>

    {{-- ── Product info ───────────────────────────────────────────────────── --}}
    <div class="pt-4 flex flex-col flex-1">

        {{-- Category — tiny, spaced label ── --}}
        @if($showCategory && $product->category)
        <a href="{{ route('shop', ['categoryId' => $product->category_id]) }}"
           class="text-[9px] tracking-widest uppercase text-bronze mb-1.5 hover:text-bronze-dark transition">
            {{ $product->category->name }}
        </a>
        @endif

        {{-- Name ── --}}
        <h3 class="font-medium text-ink text-sm leading-snug mb-1
                   group-hover:text-bronze transition-colors duration-300">
            <a href="{{ route('product.detail', $product->id) }}"
               class="after:absolute after:inset-0">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Description (faint, minimal) ── --}}
        @if(!empty($product->description))
        <p class="text-[11px] text-ink-muted leading-relaxed line-clamp-2 mt-1 mb-3">
            {{ $product->description }}
        </p>
        @endif

        {{-- Price — pushed to bottom ── --}}
        <div class="mt-auto pt-3 border-t border-border flex items-center justify-between">
            <span class="headline text-base font-medium text-ink">
                {{ number_format($product->price, 0, ',', '.') }}<span class="text-xs font-sans ml-0.5 text-ink-muted">₫</span>
            </span>

            {{-- Wishlist placeholder (static, UX signal) ── --}}
            <button type="button" aria-label="Yêu thích"
                    class="text-ink-muted/40 hover:text-bronze transition-colors duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
            </button>
        </div>
    </div>
</article>

{{-- ── Per-card styles (injected once) ─────────────────────────────────────── --}}
@once
@push('styles')
<style>
    .product-card { position: relative; }

    /* Flat shadow on hover — no heavy box-shadow, just a faint border glow */
    .product-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border: 1px solid transparent;
        pointer-events: none;
        transition: border-color 0.35s;
    }
    .product-card:hover::after {
        border-color: rgba(156, 124, 82, 0.25);
    }
</style>
@endpush
@endonce
