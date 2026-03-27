@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Chi tiết đơn hàng #{{ $order->id }}</h1>
        <a href="{{ route('delivery.orders') }}" class="text-sm text-blue-600 hover:underline">← Quay lại</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700">Thông tin đơn hàng</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Mã đơn:</span> <span class="font-mono">#{{ $order->id }}</span></div>
            <div><span class="text-gray-500">Ngày đặt:</span> {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div><span class="text-gray-500">Trạng thái:</span> 
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">{{ $order->status_label }}</span>
            </div>
            <div><span class="text-gray-500">Tổng tiền:</span> <strong>{{ number_format($order->total_price, 0, ',', '.') }}₫</strong></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700">Thông tin khách hàng</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Họ tên:</span> {{ $order->full_name ?? $order->user?->full_name }}</div>
            <div><span class="text-gray-500">Số điện thoại:</span> {{ $order->phone ?? '—' }}</div>
            <div><span class="text-gray-500">Địa chỉ:</span> {{ $order->address ?? '—' }}</div>
            <div><span class="text-gray-500">Ghi chú:</span> {{ $order->note ?? '—' }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700">Sản phẩm</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="px-6 py-3 text-left">Sản phẩm</th>
                        <th class="px-6 py-3 text-center">Số lượng</th>
                        <th class="px-6 py-3 text-right">Đơn giá</th>
                        <th class="px-6 py-3 text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="px-6 py-3">{{ $item->product->name }}</td>
                        <td class="px-6 py-3 text-center">{{ $item->quantity }}</td>
                        <td class="px-6 py-3 text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                        <td class="px-6 py-3 text-right font-semibold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-semibold">Tổng cộng:</td>
                        <td class="px-6 py-3 text-right font-bold text-blue-600">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if(in_array($order->status->value, ['PENDING','CONFIRMED','PROCESSING','DELIVERING']))
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Cập nhật trạng thái đơn hàng</h3>
        <form method="POST" action="{{ route('delivery.order.status', $order->id) }}" class="flex gap-3">
            @csrf
            <select name="status" class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                @foreach($statuses as $s)
                <option value="{{ $s->value }}" {{ $order->status->value === $s->value ? 'selected' : '' }}>
                    {{ $s->label() }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                Cập nhật
            </button>
        </form>
    </div>
    @endif
</div>
@endsection