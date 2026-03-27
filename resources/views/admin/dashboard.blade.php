@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', ' Dashboard')

@section('content')

{{-- ── KPI Cards — mirrors Java model.addAttribute("totalProducts", ...) ────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    @php
    $cards = [
        ['label' => 'Sản phẩm',       'value' => $totalProducts,                              'icon' => '', 'color' => 'blue'],
        ['label' => 'Người dùng',     'value' => $totalUsers,                                 'icon' => '', 'color' => 'green'],
        ['label' => 'Đơn hàng',       'value' => $totalOrders,                                'icon' => '', 'color' => 'indigo'],
        ['label' => 'Đơn chờ xử lý', 'value' => $pendingOrders,                              'icon' => '', 'color' => 'yellow'],
    ];
    $colorMap = [
        'blue'   => 'bg-blue-50 text-blue-600',
        'green'  => 'bg-green-50 text-green-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'yellow' => 'bg-yellow-50 text-yellow-600',
    ];
    @endphp

    @foreach($cards as $card)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl {{ $colorMap[$card['color']] }}">
            {{ $card['icon'] }}
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($card['value']) }}</p>
            <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Revenue card --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"></div>
    <div>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalRevenue, 0, ',', '.') }}₫</p>
        <p class="text-sm text-gray-500">Tổng doanh thu</p>
    </div>
</div>

{{-- ── Recent Orders — mirrors Java recentOrders from findAll(PageRequest.of(0,5)) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Đơn hàng gần đây</h3>
        <a href="{{ route('admin.orders') }}" class="text-sm text-blue-600 hover:underline">Xem tất cả →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <th class="px-6 py-3 text-left">Mã đơn</th>
                    <th class="px-6 py-3 text-left">Khách hàng</th>
                    <th class="px-6 py-3 text-left">Tổng tiền</th>
                    <th class="px-6 py-3 text-left">Trạng thái</th>
                    <th class="px-6 py-3 text-left">Ngày đặt</th>
                    <th class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{-- th:each="order : ${recentOrders}" --}}
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 font-mono text-gray-600">#{{ $order->id }}</td>
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $order->full_name ?? $order->user?->full_name }}</td>
                    <td class="px-6 py-3 text-blue-600 font-semibold">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="text-blue-600 hover:underline text-xs">Chi tiết</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Chưa có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
