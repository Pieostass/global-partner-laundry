@extends('layouts.admin')
@section('title', 'Cài đặt')
@section('page-title', '⚙️ Cài đặt giao diện')

@section('content')
<form method="POST" action="{{ route('admin.settings.save') }}" class="max-w-3xl space-y-6">
    @csrf

    @php
    // Gom nhóm các config để hiển thị khoa học
    $groups = [
        'Thương hiệu' => ['site_name', 'site_tagline'],
        'Giao diện'   => ['background_color', 'primary_color', 'accent_color', 'navbar_color'],
        'Logo'        => ['logo_url'],
        'Footer'      => ['footer_address', 'footer_phone', 'footer_email', 'footer_hours'],
        'Hero'        => ['hero_title', 'hero_subtitle', 'hero_btn1_text', 'hero_btn1_url', 'hero_btn2_text', 'hero_btn2_url'],
        'USP'         => ['usp1_icon', 'usp1_title', 'usp1_desc', 'usp2_icon', 'usp2_title', 'usp2_desc', 'usp3_icon', 'usp3_title', 'usp3_desc', 'usp4_icon', 'usp4_title', 'usp4_desc'],
    ];
    @endphp

    @foreach($groups as $groupName => $keys)
        @php
            $groupConfigs = collect($configs)->filter(fn($cfg) => in_array($cfg->config_key, $keys));
        @endphp
        @if($groupConfigs->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">{{ $groupName }}</h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($groupConfigs as $cfg)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $cfg->description ?? $cfg->config_key }}
                        <span class="text-xs text-gray-400 font-mono ml-1">[{{ $cfg->config_key }}]</span>
                    </label>
                    @if(str_ends_with($cfg->config_key, '_color'))
                        <div class="flex items-center gap-3">
                            <input type="color" name="{{ $cfg->config_key }}" value="{{ $cfg->config_value }}"
                                   class="h-10 w-16 rounded-lg border border-gray-300 cursor-pointer">
                            <input type="text" name="{{ $cfg->config_key }}" value="{{ $cfg->config_value }}"
                                   class="flex-1 px-4 py-2 rounded-xl border border-gray-300 text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    @elseif($cfg->config_key === 'logo_url')
                        <div>
                            <input type="text" name="logo_url" value="{{ $cfg->config_value }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                   placeholder="https://example.com/logo.png">
                            @if(!empty($cfg->config_value))
                            <div class="mt-2">
                                <img src="{{ $cfg->config_value }}" alt="Logo preview" class="h-12 object-contain border border-gray-200 rounded-lg p-1">
                            </div>
                            @endif
                        </div>
                    @elseif(strlen($cfg->config_value ?? '') > 100 || str_contains($cfg->config_key, 'subtitle') || str_contains($cfg->config_key, 'title'))
                        <textarea name="{{ $cfg->config_key }}" rows="2"
                                  class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none">{{ $cfg->config_value }}</textarea>
                    @else
                        <input type="text" name="{{ $cfg->config_key }}" value="{{ $cfg->config_value }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    <div class="flex gap-3">
        <button type="submit"
                class="px-6 py-2.5 rounded-xl text-white font-medium text-sm hover:opacity-90 transition"
                style="background: {{ $siteConfig['primary_color'] ?? '#1352a1' }}">
             Lưu cài đặt
        </button>
    </div>
</form>
@endsection