@extends('layouts.app')
@section('title', '404 - Không tìm thấy trang')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-bronze mb-4">404</h1>
        <p class="text-xl text-ink mb-6">Không tìm thấy trang bạn yêu cầu.</p>
        <p class="text-ink-muted mb-8">Trang có thể đã bị xóa hoặc đường dẫn không đúng.</p>
        <a href="{{ route('home') }}" class="btn-bronze">Về trang chủ</a>
    </div>
</div>
@endsection