@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Product image --}}
        <div class="rounded-2xl overflow-hidden bg-gray-50 shadow-md">
            <img src="{{ $product->image_src }}" alt="{{ $product->name }}" class="w-full h-auto object-cover">
        </div>

        {{-- Product info --}}
        <div>
            <div class="mb-4">
                <p class="text-sm text-amber-600 font-medium mb-1">{{ $product->category?->name }}</p>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <p class="text-2xl font-semibold text-amber-700">{{ number_format($product->price, 0, ',', '.') }}₫</p>
            </div>

            <div class="prose prose-gray max-w-none mb-6">
                <p>{{ $product->description }}</p>
            </div>

            <div class="flex items-center gap-4 mb-8">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                    <button type="button" id="decrement-qty" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition">-</button>
                    <input type="number" id="product-qty" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none">
                    <button type="button" id="increment-qty" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition">+</button>
                </div>
                <form method="POST" action="{{ route('cart.add') }}" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="cart-quantity" value="1">
                    <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">Thêm vào giỏ hàng</button>
                </form>
            </div>

            <div class="border-t border-gray-200 pt-6 text-sm text-gray-500">
                <p>✅ Hàng có sẵn, giao hàng nhanh chóng</p>
                <p>✅ Đổi trả trong 7 ngày nếu sản phẩm lỗi</p>
            </div>
        </div>
    </div>

    {{-- Related products --}}
    @if($relatedProducts->isNotEmpty())
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Sản phẩm liên quan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                @include('components.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    const qtyInput = document.getElementById('product-qty');
    const cartQty = document.getElementById('cart-quantity');
    const decrementBtn = document.getElementById('decrement-qty');
    const incrementBtn = document.getElementById('increment-qty');

    function updateCartQty() {
        let val = parseInt(qtyInput.value);
        if (isNaN(val)) val = 1;
        if (val < 1) val = 1;
        if (val > {{ $product->stock_quantity }}) val = {{ $product->stock_quantity }};
        qtyInput.value = val;
        cartQty.value = val;
    }

    decrementBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) qtyInput.value = val - 1;
        updateCartQty();
    });
    incrementBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val < {{ $product->stock_quantity }}) qtyInput.value = val + 1;
        updateCartQty();
    });
    qtyInput.addEventListener('change', updateCartQty);
</script>
@endsection