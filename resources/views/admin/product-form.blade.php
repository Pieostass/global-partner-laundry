@extends('layouts.admin')
@section('title', $product ? 'Sửa sản phẩm' : 'Thêm sản phẩm')
@section('page-title', $product ? ' Sửa sản phẩm' : ' Thêm sản phẩm mới')

@section('content')

{{-- Form action: create = POST /admin/products/new, edit = POST /admin/products/edit/{id} --}}
<form method="POST"
      action="{{ $product ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      class="max-w-3xl">
    @csrf

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

        {{-- Name — mirrors @NotBlank @Size(max=200) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $product?->name) }}" required maxlength="200"
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm @error('name') border-red-400 @enderror">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Category — mirrors @NotNull categoryId --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục <span class="text-red-500">*</span></label>
            <select name="category_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm @error('category_id') border-red-400 @enderror">
                <option value="">— Chọn danh mục —</option>
                {{-- th:each="cat : ${categories}" --}}
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product?->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Price + Stock --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá (₫) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $product?->price) }}" required min="0.01" step="1"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm @error('price') border-red-400 @enderror">
                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tồn kho <span class="text-red-500">*</span></label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product?->stock_quantity) }}" required min="0"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm @error('stock_quantity') border-red-400 @enderror">
                @error('stock_quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
            <textarea name="description" rows="4" maxlength="5000"
                      class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none">{{ old('description', $product?->description) }}</textarea>
        </div>

        {{-- Image upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh sản phẩm</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

            {{-- URL fallback (for external images) --}}
            <div class="mt-2">
                <label class="text-xs text-gray-500">Hoặc nhập URL ảnh:</label>
                <input type="text" name="image_url" value="{{ old('image_url', $product?->image_url) }}"
                       placeholder="https://..."
                       class="w-full mt-1 px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            </div>

            {{-- Preview current image if editing --}}
            @if($product?->image_url)
            <div class="mt-3">
                <p class="text-xs text-gray-500 mb-1">Ảnh hiện tại:</p>
                <img src="{{ $product->image_src }}" alt="Current"
                     class="w-24 h-24 object-cover rounded-xl border border-gray-200">
            </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 mt-6">
        <button type="submit"
                class="px-6 py-2.5 rounded-xl text-white text-sm font-medium hover:opacity-90 transition"
                style="background: {{ $siteConfig['primary_color'] ?? '#1352a1' }}">
            {{ $product ? ' Lưu thay đổi' : ' Tạo sản phẩm' }}
        </button>
        <a href="{{ route('admin.products') }}"
           class="px-6 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
            Huỷ
        </a>
    </div>
</form>
@endsection
