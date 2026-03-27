@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            {{-- Header --}}
            <div class="px-8 pt-8 pb-6 text-center" style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                <div class="text-5xl mb-3"></div>
                <h1 class="text-2xl font-bold text-white">Đăng nhập</h1>
                <p class="text-blue-100 text-sm mt-1">{{ $siteConfig['site_tagline'] ?? '' }}</p>
            </div>

            <div class="px-8 py-6">

                {{-- Session alerts — mirrors Java @RequestParam error/logout/registered --}}
                @if(session('logoutSuccess') || $errors->has('username'))
                    @if(session('logoutSuccess'))
                    <div class="flash-alert mb-4 flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl px-4 py-3 text-sm">
                         Đã đăng xuất thành công.
                    </div>
                    @endif
                    @if($errors->has('username'))
                    <div class="flash-alert mb-4 flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 rounded-xl px-4 py-3 text-sm">
                         {{ $errors->first('username') }}
                    </div>
                    @endif
                @endif

                @if(session('success'))
                <div class="flash-alert mb-4 flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 rounded-xl px-4 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
                @endif

                {{-- Form — mirrors Java .loginProcessingUrl("/auth/login") --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Username — mirrors th:field="*{username}" --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Tên đăng nhập
                        </label>
                        <input type="text" id="username" name="username"
                               value="{{ old('username') }}"
                               autocomplete="username"
                               required autofocus
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition text-sm @error('username') border-red-400 @enderror"
                               placeholder="Nhập tên đăng nhập">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Mật khẩu
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                   autocomplete="current-password"
                                   required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition text-sm pr-10"
                                   placeholder="Nhập mật khẩu">
                            <button type="button" onclick="togglePwd(this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">👁</button>
                        </div>
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-xl text-white font-semibold text-sm transition hover:opacity-90 active:scale-95"
                            style="background: linear-gradient(135deg, {{ $siteConfig['primary_color'] ?? '#1352a1' }}, {{ $siteConfig['accent_color'] ?? '#2e88f6' }})">
                        Đăng nhập
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-5">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePwd(btn) {
    const input = btn.previousElementSibling;
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
}
</script>
@endsection
