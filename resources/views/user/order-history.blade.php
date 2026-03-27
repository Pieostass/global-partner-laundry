@extends('layouts.app')
@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📦 Đơn hàng của tôi</h1>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center justify-between">
        <span>✅ {{ session('success') }}</span>
        <button onclick="this.closest('div').remove()" class="text-green-500">✕</button>
    </div>
    @endif

    @if($orders->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="text-5xl mb-4">📦</div>
        <p class="text-gray-500">Bạn chưa có đơn hàng nào.</p>
        <a href="{{ route('shop') }}" class="mt-4 inline-block px-5 py-2 bg-blue-600 text-white rounded-lg text-sm">Mua sắm ngay</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex flex-wrap justify-between items-start gap-3">
                <div>
                    <p class="text-xs text-gray-400">Mã đơn: <span class="font-mono">#{{ $order->id }}</span></p>
                    <p class="text-sm font-medium mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <div class="mt-3 space-y-2">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</span>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between font-semibold">
                <span>Tổng cộng</span>
                <span class="text-blue-600">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
            </div>

            @if($order->status->value == 'PENDING')
            <div class="mt-3 text-xs text-yellow-600 bg-yellow-50 p-2 rounded">
                ⏳ Đơn hàng đang chờ xác nhận. Vui lòng chờ trong giây lát.
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection