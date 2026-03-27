@extends('layouts.app')
@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <div class="px-8 pt-8 pb-6 text-center" style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                <div class="text-5xl mb-3"></div>
                <h1 class="text-2xl font-bold text-white">Tạo tài khoản</h1>
                <p class="text-blue-100 text-sm mt-1">Tham gia {{ $siteConfig['site_name'] ?? 'LaundryShop' }} ngay hôm nay</p>
            </div>

            <div class="px-8 py-6">
                @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Form — mirrors Java RegisterDto --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Username — mirrors @Pattern regexp="^[a-zA-Z0-9_]+$" --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên đăng nhập <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               minlength="3" maxlength="50" pattern="^[a-zA-Z0-9_]+"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm @error('username') border-red-400 bg-red-50 @enderror"
                               placeholder="Chỉ chữ cái, số, gạch dưới">
                        @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Full name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required maxlength="150"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm @error('full_name') border-red-400 bg-red-50 @enderror"
                               placeholder="Nguyễn Văn A">
                        @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required maxlength="150"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm @error('email') border-red-400 bg-red-50 @enderror"
                               placeholder="example@email.com">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Phone — mirrors @Pattern regexp="^(\+84|0)[0-9]{9,10}$" --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm @error('phone') border-red-400 bg-red-50 @enderror"
                               placeholder="0901 234 567">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password — mirrors @Size(min=8) + mixedCase + numbers --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required minlength="8"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm pr-10 @error('password') border-red-400 bg-red-50 @enderror"
                                   placeholder="Ít nhất 8 ký tự, có Hoa/thường/số">
                            <button type="button" onclick="togglePwd('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">👁</button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password confirm --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm pr-10"
                                   placeholder="Nhập lại mật khẩu">
                            <button type="button" onclick="togglePwd('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">👁</button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-xl text-white font-semibold text-sm transition hover:opacity-90 active:scale-95"
                            style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                        Tạo tài khoản
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-5">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    input.type  = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
</script>
@endsection
