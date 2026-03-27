@extends('layouts.admin')
@section('title', 'Đơn hàng')
@section('page-title', ' Quản lý đơn hàng')

@section('content')

{{-- ── Status filter tabs — mirrors Java model.addAttribute("statuses", OrderStatus.values()) --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.orders') }}"
       class="px-4 py-1.5 rounded-full text-xs font-medium transition {{ !$currentStatus ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        Tất cả
    </a>
    {{-- th:each="s : ${statuses}" --}}
    @foreach($statuses as $s)
    <a href="{{ route('admin.orders', ['status' => $s->value]) }}"
       class="px-4 py-1.5 rounded-full text-xs font-medium transition {{ $currentStatus === $s->value ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        {{ $s->label() }}
    </a>
    @endforeach
</div>

{{-- ── Orders table ─────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <th class="px-5 py-3 text-left">Mã đơn</th>
                    <th class="px-5 py-3 text-left">Khách hàng</th>
                    <th class="px-5 py-3 text-left">SĐT</th>
                    <th class="px-5 py-3 text-right">Tổng tiền</th>
                    <th class="px-5 py-3 text-center">Trạng thái</th>
                    <th class="px-5 py-3 text-left">Ngày đặt</th>
                    <th class="px-5 py-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-gray-600">#{{ $order->id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $order->full_name ?? $order->user?->full_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $order->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-blue-600">
                        {{ number_format($order->total_price, 0, ',', '.') }}₫
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium hover:bg-blue-100">
                                Chi tiết
                            </a>
                            {{-- Inline status update — mirrors Java POST /orders/{id}/status --}}
                            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="flex items-center gap-1">
                                @csrf
                                <select name="status" onchange="this.form.submit()"
                                        class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:ring-1 focus:ring-blue-500 outline-none">
                                    @foreach($statuses as $s)
                                    <option value="{{ $s->value }}" {{ $order->status?->value === $s->value ? 'selected' : '' }}>
                                        {{ $s->label() }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2"></div>
                        <p>Chưa có đơn hàng nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $orders->withQueryString()->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
