@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION — mirrors Thymeleaf th:text="${siteConfig['hero_title']}" --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden py-20 md:py-28"
         style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }} 0%, {{ $siteConfig['accent_color'] ?? '#2e88f6' }} 100%)">

    {{-- Decorative blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full opacity-10 bg-white"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full opacity-10 bg-white"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4">
                    {!! nl2br(e($siteConfig['hero_title'] ?? "Giặt Sạch, Giao Nhanh\nTận Cửa Nhà Bạn")) !!}
                </h1>
                <p class="text-blue-100 text-lg mb-8 leading-relaxed">
                    {{ $siteConfig['hero_subtitle'] ?? '' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ $siteConfig['hero_btn1_url'] ?? '/shop' }}"
                       class="px-7 py-3.5 bg-white font-semibold rounded-full text-sm shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5"
                       style="color: {{ $siteConfig['primary_color'] ?? '#1352a1' }}">
                        {{ $siteConfig['hero_btn1_text'] ?? 'Mua ngay' }} →
                    </a>
                    <a href="{{ $siteConfig['hero_btn2_url'] ?? '/flash-sale' }}"
                       class="px-7 py-3.5 border-2 border-white/60 text-white font-semibold rounded-full text-sm hover:bg-white/10 transition">
                        ⚡ {{ $siteConfig['hero_btn2_text'] ?? 'Flash Sale' }}
                    </a>
                </div>
            </div>

            {{-- Hero image — mirrors th:if="${featuredProduct}" --}}
            @if($featuredProduct)
            <div class="hidden lg:flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 rounded-3xl blur-3xl opacity-30 bg-white transform scale-90"></div>
                    <img src="{{ $featuredProduct->image_src }}"
                         alt="{{ $featuredProduct->name }}"
                         class="relative w-80 h-80 object-cover rounded-3xl shadow-2xl">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- USP BADGES — mirrors th:text="${siteConfig['usp1_title']}" --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-10 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([1,2,3,4] as $i)
            <div class="text-center p-4">
                <div class="text-3xl mb-2">{{ $siteConfig["usp{$i}_icon"] ?? '' }}</div>
                <h3 class="font-bold text-gray-800 text-sm">{{ $siteConfig["usp{$i}_title"] ?? '' }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $siteConfig["usp{$i}_desc"] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- FEATURED PRODUCTS — mirrors th:each="product : ${featuredProducts}" --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Sản phẩm nổi bật</h2>
            <p class="text-gray-500 text-sm mt-1">Được yêu thích nhất tại {{ $siteConfig['site_name'] ?? 'LaundryShop' }}</p>
        </div>
        <a href="{{ route('shop') }}" class="text-sm font-medium text-blue-600 hover:underline">Xem tất cả →</a>
    </div>

    @if($featuredProducts->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <div class="text-5xl mb-4">📦</div>
        <p>Chưa có sản phẩm nào.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- th:each="product : ${featuredProducts}" --}}
        @foreach($featuredProducts as $product)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover border border-gray-100">
            <a href="{{ route('product.detail', $product->id) }}">
                {{-- th:src="@{${product.imageUrl}}" --}}
                <img src="{{ $product->image_src }}"
                     alt="{{ $product->name }}"
                     class="w-full h-48 object-cover">
            </a>
            <div class="p-4">
                <p class="text-xs text-blue-600 font-medium mb-1">{{ $product->category?->name ?? '' }}</p>
                <h3 class="font-semibold text-gray-800 text-sm line-clamp-2 mb-2">
                    <a href="{{ route('product.detail', $product->id) }}" class="hover:text-blue-600">
                        {{ $product->name }}
                    </a>
                </h3>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-blue-600 font-bold text-lg">
                        {{ number_format($product->price, 0, ',', '.') }}₫
                    </span>
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        @auth
                        <button type="submit"
                                class="px-3 py-1.5 rounded-full text-white text-xs font-medium transition hover:opacity-80"
                                style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">
                            + Giỏ
                        </button>
                        @else
                        <a href="{{ route('login') }}"
                           class="px-3 py-1.5 rounded-full text-white text-xs font-medium transition hover:opacity-80"
                           style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">
                            Mua ngay
                        </a>
                        @endauth
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- CONTACT SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-16 bg-gray-50" id="contact">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Liên hệ với chúng tôi</h2>
        <p class="text-center text-gray-500 text-sm mb-8">Chúng tôi sẽ phản hồi trong vòng 24 giờ</p>

        <form method="POST" action="{{ route('contact') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ tên <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung</label>
                <textarea name="message" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"
                          placeholder="Bạn cần tư vấn gì?"></textarea>
            </div>
            <button type="submit"
                    class="w-full py-3 rounded-xl text-white font-semibold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                Gửi tin nhắn
            </button>
        </form>
    </div>
</section>

@endsection
