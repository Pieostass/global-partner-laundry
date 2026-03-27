@extends('layouts.admin')
@section('title', 'Sản phẩm')
@section('page-title', ' Quản lý sản phẩm')

@section('content')

{{-- ── Toolbar ─────────────────────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    {{-- Search + filter — mirrors Java @RequestParam keyword, categoryId --}}
    <form method="GET" action="{{ route('admin.products') }}" class="flex flex-wrap gap-2">
        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Tìm sản phẩm..."
               class="px-4 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 outline-none w-52">

        <select name="categoryId"
                class="px-4 py-2 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                onchange="this.form.submit()">
            <option value="">Tất cả danh mục</option>
            {{-- th:each="cat : ${categories}" --}}
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>

        <button type="submit" class="px-4 py-2 rounded-xl text-white text-sm font-medium" style="background: {{ $siteConfig['accent_color'] ?? '#2e88f6' }}">Lọc</button>
        @if($keyword || $categoryId)
        <a href="{{ route('admin.products') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50">Xoá lọc</a>
        @endif
    </form>

    <a href="{{ route('admin.products.create') }}"
       class="flex items-center gap-2 px-5 py-2 rounded-xl text-white text-sm font-medium hover:opacity-90 transition flex-shrink-0"
       style="background: {{ $siteConfig['primary_color'] ?? '#1352a1' }}">
        + Thêm sản phẩm
    </a>
</div>

{{-- ── Products table ──────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <th class="px-5 py-3 text-left">Ảnh</th>
                    <th class="px-5 py-3 text-left">Tên sản phẩm</th>
                    <th class="px-5 py-3 text-left">Danh mục</th>
                    <th class="px-5 py-3 text-right">Giá</th>
                    <th class="px-5 py-3 text-right">Tồn kho</th>
                    <th class="px-5 py-3 text-center">Trạng thái</th>
                    <th class="px-5 py-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{-- th:each="product : ${products}" --}}
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <img src="{{ $product->image_src }}"
                             alt="{{ $product->name }}"
                             class="w-12 h-12 object-cover rounded-xl">
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400 line-clamp-1">{{ $product->description }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-blue-600">
                        {{ number_format($product->price, 0, ',', '.') }}₫
                    </td>
                    <td class="px-5 py-3 text-right text-gray-700">{{ $product->stock_quantity }}</td>
                    <td class="px-5 py-3 text-center">
                        @if($product->active)
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Hoạt động</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Ẩn</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium hover:bg-blue-100 transition">
                                Sửa
                            </a>
                            <form method="POST" action="{{ route('admin.products.delete', $product->id) }}">
                                @csrf
                                <button type="submit"
                                        data-confirm="Vô hiệu hóa sản phẩm này?"
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-medium hover:bg-red-100 transition">
                                    Ẩn
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2"></div>
                        <p>Chưa có sản phẩm nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $products->withQueryString()->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
