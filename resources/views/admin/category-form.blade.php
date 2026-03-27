@extends('layouts.admin')
@section('title', isset($category) ? 'Sửa danh mục' : 'Thêm danh mục')
@section('page-title', isset($category) ? ' Sửa danh mục' : ' Thêm danh mục')

@section('content')
<form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $category->description ?? '') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700">{{ isset($category) ? 'Cập nhật' : 'Tạo danh mục' }}</button>
            <a href="{{ route('admin.categories') }}" class="px-5 py-2 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50">Hủy</a>
        </div>
    </div>
</form>
@endsection