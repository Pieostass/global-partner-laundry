@extends('layouts.app')
@section('title', 'Flash Sale')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 text-gold text-sm font-medium tracking-wider uppercase mb-4">
            <span class="w-8 h-px bg-gold/30"></span>
            <span>⚡ Thời gian có hạn</span>
            <span class="w-8 h-px bg-gold/30"></span>
        </div>
        <h1 class="headline text-4xl md:text-5xl font-medium text-ink mb-4">Flash Sale</h1>
        <p class="text-ink-muted max-w-2xl mx-auto text-sm leading-relaxed">
            Những sản phẩm được giảm giá mạnh nhất chỉ trong thời gian ngắn. Nhanh tay sở hữu ngay!
        </p>
    </div>

    @if($products->isEmpty())
    <div class="text-center py-20 bg-cream rounded-2xl shadow-sm border border-border-soft">
        <div class="text-6xl mb-4">⏳</div>
        <p class="text-ink-muted">Chưa có sản phẩm flash sale nào.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="group bg-white rounded-2xl border border-border-soft hover:shadow-md transition-all duration-300">
            <a href="{{ route('product.detail', $product->id) }}" class="block overflow-hidden rounded-t-2xl">
                <img src="{{ $product->image_src }}" alt="{{ $product->name }}" 
                     class="w-full aspect-square object-cover transition-transform duration-500 group-hover:scale-105">
            </a>
            <div class="p-5">
                <p class="text-xs text-bronze font-medium mb-1">{{ $product->category?->name ?? '' }}</p>
                <h3 class="font-semibold text-ink text-sm line-clamp-2">
                    <a href="{{ route('product.detail', $product->id) }}" class="hover:text-bronze transition">
                        {{ $product->name }}
                    </a>
                </h3>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-bronze font-bold text-lg">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if(isset($product->old_price))
                    <span class="text-ink-muted line-through text-xs">{{ number_format($product->old_price, 0, ',', '.') }}₫</span>
                    @endif
                </div>
                <div class="mt-4">
                    @auth
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" 
                                class="w-full py-2.5 text-center text-sm font-medium rounded-full transition-all bg-bronze text-cream hover:bg-bronze-dark active:scale-95">
                            + Thêm vào giỏ
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" 
                       class="block w-full py-2.5 text-center text-sm font-medium rounded-full transition-all bg-bronze text-cream hover:bg-bronze-dark">
                        Đăng nhập để mua
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Banner cuối trang --}}
    <div class="mt-16 text-center bg-warm-gray rounded-2xl p-8 border border-border-soft">
        <p class="text-ink-muted text-sm">Đừng bỏ lỡ cơ hội sở hữu sản phẩm chất lượng với giá ưu đãi nhất!</p>
        <a href="{{ route('shop') }}" class="inline-block mt-4 text-bronze hover:text-bronze-dark underline text-sm font-medium transition">
            Xem tất cả sản phẩm →
        </a>
    </div>
</div>
@endsection