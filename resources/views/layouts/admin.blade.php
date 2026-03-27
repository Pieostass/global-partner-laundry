<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ $siteConfig['site_name'] ?? 'LaundryShop' }} Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: {{ $siteConfig['primary_color'] ?? '#9C7C52' }}; --accent: {{ $siteConfig['accent_color'] ?? '#B8976A' }}; }
        body {
            font-family: 'Be Vietnam Pro', system-ui, -apple-system, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #d1d5db;
            transition: all 0.2s;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar-link.active {
            background: var(--accent);
            color: white;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800">
<div class="flex h-screen overflow-hidden">

    <aside class="w-64 flex-shrink-0 flex flex-col text-white shadow-xl" style="background-color: {{ $siteConfig['navbar_color'] ?? '#0a2540' }}">
        <div class="px-5 py-5 border-b border-white/10">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight"> {{ $siteConfig['site_name'] ?? 'LaundryShop' }}</a>
            <p class="text-xs text-gray-400 mt-0.5">Bảng điều khiển</p>
        </div>

        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"> Dashboard</a>
            <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}"> Sản phẩm</a>
            <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"> Danh mục</a>
            <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"> Đơn hàng</a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"> Người dùng</a>
            <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"> Cài đặt</a>
            <div class="border-t border-white/10 my-3"></div>
            <a href="{{ route('delivery.dashboard') }}" class="sidebar-link"> Giao hàng</a>
            <a href="{{ route('home') }}" class="sidebar-link"> Về trang chủ</a>
        </nav>

        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background:var(--accent)">
                    {{ strtoupper(substr(auth()->user()->full_name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->role->label() ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Đăng xuất" class="text-gray-400 hover:text-red-400 transition text-lg">⏏</button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shadow-sm">
            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</div>
        </header>

        @if(session('success') || session('error'))
        <div class="px-6 pt-4">
            @if(session('success'))
            <div class="flash-alert flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-3">
                ✅ {{ session('success') }}
                <button onclick="this.closest('.flash-alert').remove()" class="ml-auto text-green-500">✕</button>
            </div>
            @endif
            @if(session('error'))
            <div class="flash-alert flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-3">
                ⚠️ {{ session('error') }}
                <button onclick="this.closest('.flash-alert').remove()" class="ml-auto text-red-500">✕</button>
            </div>
            @endif
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-alert').forEach(el => {
        setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .5s'; setTimeout(()=>el.remove(),500); }, 4000);
    });
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => { if(!confirm(el.dataset.confirm)) e.preventDefault(); });
    });
});
</script>
@stack('scripts')
</body>
</html>