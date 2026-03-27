@extends('layouts.admin')
@section('title', 'Danh mục')
@section('page-title', ' Quản lý danh mục')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">+ Thêm danh mục</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <th class="px-5 py-3 text-left">ID</th>
                    <th class="px-5 py-3 text-left">Tên danh mục</th>
                    <th class="px-5 py-3 text-left">Mô tả</th>
                    <th class="px-5 py-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">#{{ $cat->id }}</td>
                    <td class="px-5 py-3 font-medium">{{ $cat->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $cat->description ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-blue-600 hover:underline text-xs mr-2">Sửa</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" data-confirm="Xóa danh mục này?" class="text-red-600 hover:underline text-xs">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">Chưa có danh mục nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $categories->links('vendor.pagination.custom') }}
</div>
@endsection