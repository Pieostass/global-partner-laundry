@extends('layouts.app')
@section('title', 'Giỏ hàng')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">🛒 Giỏ hàng của bạn</h1>

    @if($cartItems->isEmpty())
    {{-- Empty cart --}}
    <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="text-6xl mb-4">🛒</div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Giỏ hàng trống</h3>
        <p class="text-gray-500 text-sm mb-6">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục.</p>
        <a href="{{ route('shop') }}"
           class="inline-block px-6 py-3 rounded-xl text-white font-medium text-sm hover:opacity-90 transition"
           style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">
            Tiếp tục mua sắm
        </a>
    </div>
    @else

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ── Cart items ─────────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- th:each="item : ${cartItems}" --}}
            @foreach($cartItems as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <img src="{{ $item['image_src'] ?? asset('images/product-placeholder.png') }}"
                     alt="{{ $item['product_name'] }}"
                     class="w-20 h-20 object-cover rounded-xl flex-shrink-0">

                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm">{{ $item['product_name'] }}</h3>
                    <p class="text-blue-600 font-bold mt-1">{{ number_format($item['price'], 0, ',', '.') }}₫</p>
                </div>

                {{-- Quantity control --}}
                <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                    <button type="button" onclick="adjustQty(this, -1)"
                            class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-bold transition">−</button>
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                           min="0" max="99"
                           class="w-12 text-center border border-gray-200 rounded-lg py-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                           onchange="this.closest('form').submit()">
                    <button type="button" onclick="adjustQty(this, 1)"
                            class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-bold transition">+</button>
                </form>

                {{-- Sub-total --}}
                <div class="text-right flex-shrink-0 hidden sm:block">
                    <p class="text-xs text-gray-400">Tổng</p>
                    <p class="font-bold text-gray-800">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</p>
                </div>

                {{-- Remove --}}
                <form method="POST" action="{{ route('cart.remove') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                    <button type="submit"
                            data-confirm="Xóa sản phẩm này khỏi giỏ hàng?"
                            class="text-red-400 hover:text-red-600 transition text-lg">✕</button>
                </form>
            </div>
            @endforeach

            {{-- Clear cart --}}
            <div class="flex justify-between items-center pt-2">
                <a href="{{ route('shop') }}" class="text-sm text-blue-600 hover:underline">← Tiếp tục mua sắm</a>
                <a href="{{ route('cart.clear') }}"
                   data-confirm="Xóa toàn bộ giỏ hàng?"
                   class="text-sm text-red-500 hover:underline">Xóa tất cả</a>
            </div>
        </div>

        {{-- ── Order summary ───────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
            <h3 class="font-bold text-gray-800 mb-4">Tóm tắt đơn hàng</h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Tạm tính</span>
                    <span>{{ number_format($total, 0, ',', '.') }}₫</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Phí vận chuyển</span>
                    <span class="text-green-600 font-medium">Miễn phí</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-gray-800 text-base">
                    <span>Tổng cộng</span>
                    <span class="text-blue-600">{{ number_format($total, 0, ',', '.') }}₫</span>
                </div>
            </div>

            <a href="{{ route('checkout') }}"
               class="block w-full mt-5 py-3 rounded-xl text-white text-center font-semibold text-sm hover:opacity-90 transition"
               style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                Thanh toán →
            </a>
        </div>
    </div>

    @endif
</div>

<script>
function adjustQty(btn, delta) {
    const input = btn.closest('form').querySelector('input[name="quantity"]');
    const val   = Math.max(0, parseInt(input.value || 0) + delta);
    input.value = val;
    input.closest('form').submit();
}
</script>
@endsection
