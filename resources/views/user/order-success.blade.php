@extends('layouts.app')
@section('title', 'Đặt hàng thành công')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="text-6xl mb-4">🎉</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-500 mb-4">Cảm ơn bạn đã tin tưởng và mua sắm tại {{ $siteConfig['site_name'] ?? 'LaundryShop' }}.</p>
        <p class="text-sm text-gray-400 mb-6">Mã đơn hàng: <span class="font-mono">#{{ $orderId }}</span></p>
        <a href="{{ route('user.orders') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition">
            Xem đơn hàng của tôi
        </a>
    </div>
</div>
@endsection