@extends('layouts.app')
@section('title', 'Bảng điều khiển nhân viên')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"> Bảng điều khiển giao hàng</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-2xl font-bold text-blue-600">{{ $totalOrders }}</div>
            <div class="text-sm text-gray-500">Tổng đơn hàng</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</div>
            <div class="text-sm text-gray-500">Chờ xác nhận</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-2xl font-bold text-indigo-600">{{ $processingCount }}</div>
            <div class="text-sm text-gray-500">Đang xử lý</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-2xl font-bold text-purple-600">{{ $deliveringCount }}</div>
            <div class="text-sm text-gray-500">Đang giao</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Đơn hàng đang xử lý & đang giao</h3>
            <a href="{{ route('delivery.orders') }}" class="text-sm text-blue-600 hover:underline">Xem tất cả →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="px-6 py-3 text-left">Mã đơn</th>
                        <th class="px-6 py-3 text-left">Khách hàng</th>
                        <th class="px-6 py-3 text-left">Tổng tiền</th>
                        <th class="px-6 py-3 text-left">Trạng thái</th>
                        <th class="px-6 py-3 text-left">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($processingOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-mono text-gray-600">#{{ $order->id }}</td>
                        <td class="px-6 py-3">{{ $order->full_name ?? $order->user?->full_name }}</td>
                        <td class="px-6 py-3 text-blue-600 font-semibold">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                        <td class="px-6 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('delivery.order.show', $order->id) }}" class="text-blue-600 hover:underline text-xs">Chi tiết</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">Không có đơn hàng nào đang xử lý hoặc đang giao.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection