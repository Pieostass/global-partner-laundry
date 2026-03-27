@extends('layouts.app')
@section('title', 'Cửa hàng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">🛒 Cửa hàng</h1>
        <p class="text-gray-500 text-sm mt-1">
            Tìm thấy {{ $products->total() }} sản phẩm
            @if($keyword) cho "<strong>{{ $keyword }}</strong>" @endif
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ── Sidebar: Category filter ─────────────────────────────────────── --}}
        <aside class="lg:w-60 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 mb-4 text-sm uppercase tracking-wide">Danh mục</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('shop') }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                  {{ !$selectedCategory ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            Tất cả
                        </a>
                    </li>
                    {{-- th:each="cat : ${categories}" --}}
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('shop', ['categoryId' => $cat->id]) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                  {{ $selectedCategory == $cat->id ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            {{ $cat->name }}
                            <span class="text-xs text-gray-400">{{ $cat->products()->active()->count() }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- ── Main: Products grid ──────────────────────────────────────────── --}}
        <div class="flex-1">
            {{-- Search bar --}}
            <form method="GET" action="{{ route('shop') }}" class="mb-6 flex gap-2">
                <input type="text" name="keyword" value="{{ $keyword }}"
                       placeholder="Tìm kiếm sản phẩm..."
                       class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                @if($selectedCategory)
                <input type="hidden" name="categoryId" value="{{ $selectedCategory }}">
                @endif
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white text-sm font-medium transition hover:opacity-90"
                        style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">
                    Tìm
                </button>
                @if($keyword || $selectedCategory)
                <a href="{{ route('shop') }}"
                   class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
                    Xoá
                </a>
                @endif
            </form>

            {{-- Products --}}
            @if($products->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <div class="text-5xl mb-4">🔍</div>
                <p class="font-medium">Không tìm thấy sản phẩm nào.</p>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover border border-gray-100">
                    <a href="{{ route('product.detail', $product->id) }}">
                        <img src="{{ $product->image_src }}"
                             alt="{{ $product->name }}"
                             class="w-full h-44 object-cover">
                    </a>
                    <div class="p-4">
                        <p class="text-xs font-medium text-blue-500 mb-1">{{ $product->category?->name }}</p>
                        <h3 class="font-semibold text-gray-800 text-sm line-clamp-2">
                            <a href="{{ route('product.detail', $product->id) }}" class="hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-blue-600 font-bold">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </span>
                            @auth
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-full text-white text-xs font-medium hover:opacity-80 transition"
                                        style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">
                                    🛒 Thêm
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}"
                               class="px-3 py-1.5 rounded-full text-white text-xs font-medium hover:opacity-80 transition"
                               style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">
                               Mua ngay
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination — replaces Thymeleaf custom pagination --}}
            @if($products->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $products->withQueryString()->links('vendor.pagination.custom') }}
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
