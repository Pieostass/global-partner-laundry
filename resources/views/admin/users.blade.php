@extends('layouts.admin')
@section('title', 'Người dùng')
@section('page-title', ' Quản lý người dùng')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <th class="px-5 py-3 text-left">ID</th>
                    <th class="px-5 py-3 text-left">Họ tên</th>
                    <th class="px-5 py-3 text-left">Username</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">SĐT</th>
                    <th class="px-5 py-3 text-center">Vai trò</th>
                    <th class="px-5 py-3 text-center">Trạng thái</th>
                    <th class="px-5 py-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{-- th:each="user : ${users}" --}}
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 text-gray-500">#{{ $user->id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $user->full_name }}</td>
                    <td class="px-5 py-3 font-mono text-gray-600">{{ $user->username }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $user->role->badgeClass() }}">
                            {{ $user->role->label() }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        {{-- th:if="${user.enabled}" mirrors Java user.isEnabled() --}}
                        @if($user->active)
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Hoạt động</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600">Bị khoá</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        {{-- Don't allow toggling your own account --}}
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                            @csrf
                            <button type="submit"
                                    data-confirm="{{ $user->active ? 'Khoá tài khoản này?' : 'Mở khoá tài khoản này?' }}"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition
                                           {{ $user->active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                {{ $user->active ? 'Khoá' : 'Mở khoá' }}
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400 italic">Bạn</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">Chưa có người dùng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
