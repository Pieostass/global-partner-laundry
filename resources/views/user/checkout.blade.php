@extends('layouts.app')
@section('title', 'Thanh toán')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">🛒 Thanh toán</h1>

    @if(isset($error))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
         {{ $error }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Thông tin giao hàng</h3>
                <form method="POST" action="{{ route('checkout.place') }}" id="checkout-form">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Họ tên <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $userInfo->full_name ?? '') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $userInfo->email ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone', $userInfo->phone ?? '') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ giao hàng <span class="text-red-500">*</span></label>
                            <textarea name="address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address', $userInfo->address ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú (không bắt buộc)</label>
                            <textarea name="note" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    {{-- Hiển thị sản phẩm --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="font-semibold text-gray-800 mb-3">Sản phẩm</h4>
                        <div class="space-y-3">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item['product_name'] }} × {{ $item['quantity'] }}</span>
                                <span class="font-medium">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mt-4 pt-3 border-t border-gray-100 font-bold text-base">
                            <span>Tổng cộng</span>
                            <span class="text-blue-600">{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="font-semibold text-gray-800 mb-3">Chọn phương thức thanh toán</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cod" checked class="w-4 h-4 text-blue-600">
                                <span class="text-sm">Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank" class="w-4 h-4 text-blue-600">
                                <span class="text-sm">Chuyển khoản ngân hàng</span>
                            </label>
                        </div>
                    </div>

                    {{-- Thông tin chuyển khoản (ẩn/hiện bằng JS) --}}
                    <div id="bank-info" class="mt-4 p-4 bg-gray-50 rounded-xl hidden">
                        <p class="text-sm font-medium mb-2">💳 Thông tin chuyển khoản:</p>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li><strong>Ngân hàng:</strong> Vietcombank - CN Hà Nội</li>
                            <li><strong>Số tài khoản:</strong> 1234 5678 9012 3456</li>
                            <li><strong>Chủ tài khoản:</strong> CÔNG TY TNHH GLOBAL PARTNER</li>
                            <li><strong>Nội dung:</strong> <span class="font-mono">[MÃ ĐƠN] + HỌ TÊN</span></li>
                        </ul>
                        <div class="mt-3 text-xs text-yellow-700 bg-yellow-50 p-2 rounded">
                            <strong>Lưu ý:</strong> Sau khi chuyển khoản, vui lòng giữ lại ảnh chụp màn hình để đối chiếu.
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-8 py-3 rounded-xl text-white font-semibold text-sm transition hover:opacity-90" style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                        Xác nhận đặt hàng →
                    </button>
                </form>
            </div>
        </div>

        {{-- Sidebar: chính sách giao hàng --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="font-semibold text-gray-800 mb-3">🚚 Chính sách giao hàng</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>✓ Miễn phí vận chuyển đơn hàng từ 300.000₫</li>
                    <li>✓ Giao hàng trong vòng 2-3 ngày làm việc</li>
                    <li>✓ Đổi trả miễn phí trong 7 ngày</li>
                </ul>
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Hỗ trợ: <a href="tel:19001234" class="text-blue-600">1900 1234</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const radioCod = document.querySelector('input[value="cod"]');
    const radioBank = document.querySelector('input[value="bank"]');
    const bankInfo = document.getElementById('bank-info');

    function toggleBankInfo() {
        if (radioBank.checked) {
            bankInfo.classList.remove('hidden');
        } else {
            bankInfo.classList.add('hidden');
        }
    }

    radioCod.addEventListener('change', toggleBankInfo);
    radioBank.addEventListener('change', toggleBankInfo);
    toggleBankInfo(); // initial state
</script>
@endsection