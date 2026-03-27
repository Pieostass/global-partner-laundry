@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng #' . $order->id)
@section('page-title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
            <p><strong>Khách hàng:</strong> {{ $order->full_name ?? $order->user?->full_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone ?? '—' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address ?? '—' }}</p>
            <p><strong>Ghi chú:</strong> {{ $order->note ?? '—' }}</p>
        </div>
        <div>
            <p><strong>Trạng thái:</strong> <span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">{{ $order->status_label }}</span></p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Tổng tiền:</strong> <strong>{{ number_format($order->total_price, 0, ',', '.') }}₫</strong></p>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold text-gray-800 mb-3">Sản phẩm</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left">Sản phẩm</th>
                    <th class="px-4 py-2 text-center">Số lượng</th>
                    <th class="px-4 py-2 text-right">Đơn giá</th>
                    <th class="px-4 py-2 text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $item->product->name }}</td>
                    <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td class="px-4 py-2 text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Quay lại</a>
    </div>
</div>
@endsection