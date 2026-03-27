@extends('layouts.app')
@section('title', $siteConfig['site_name'] ?? 'LaundryShop')

@push('styles')
<style>
    /* ── Hero ──────────────────────────────────────────────────────────── */
    .hero-section {
        min-height: calc(100vh - var(--nav-height));
        display: grid;
        grid-template-columns: 1fr 1fr;
        overflow: hidden;
        position: relative;
    }
    @media (max-width: 1023px) {
        .hero-section { grid-template-columns: 1fr; min-height: 80vh; }
        .hero-image-col { display: none; }
    }

    /* ── Grain texture overlay ──────────────────────────────────────────── */
    .hero-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }

    /* ── Marquee ────────────────────────────────────────────────────────── */
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .marquee-track { animation: marquee 28s linear infinite; display: flex; width: max-content; }
    .marquee-track:hover { animation-play-state: paused; }

    /* ── Image parallax hint ────────────────────────────────────────────── */
    .hero-img {
        transition: transform 0.1s linear;
        will-change: transform;
    }

    /* ── Section reveal ─────────────────────────────────────────────────── */
    .section-label {
        font-size: 0.65rem;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: var(--bronze);
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 1  HERO (với ảnh nền)                                                     --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="hero-section"
        style="background-image: url('/images/background_img.jpg');"; background-size: cover; background-position: center;">

    {{-- Lớp phủ tối để chữ nổi rõ hơn --}}
    <div class="absolute inset-0 bg-black/30 z-0"></div>

    {{-- ── Left: editorial copy ── --}}
    <div class="hero-left relative z-10 flex flex-col justify-center
                px-10 lg:px-20 xl:px-28 py-24">

        {{-- Eyebrow ── --}}
        <p class="section-label mb-8 fade-up">
            {{ $siteConfig['site_tagline'] ?? 'Dịch vụ giặt là cao cấp' }}
        </p>

        {{-- Main headline ── --}}
        <h1 class="headline text-5xl lg:text-6xl xl:text-7xl font-medium text-white
                   leading-[1.08] tracking-tight mb-8 fade-up fade-up-d1">
            {!! nl2br(e($siteConfig['hero_title'] ?? "Tinh tế trong\ntừng đường chỉ")) !!}
        </h1>

        {{-- Sub-copy ── --}}
        <p class="text-white/80 text-[15px] leading-loose max-w-sm mb-12 fade-up fade-up-d2">
            {{ $siteConfig['hero_subtitle'] ?? 'Chúng tôi chăm sóc từng sợi vải như chăm sóc điều bạn trân quý nhất.' }}
        </p>

        {{-- CTA row ── --}}
        <div class="flex flex-wrap items-center gap-4 fade-up fade-up-d3">
            <a href="{{ $siteConfig['hero_btn1_url'] ?? route('shop') }}" class="btn-bronze">
                {{ $siteConfig['hero_btn1_text'] ?? 'Khám phá ngay' }}
            </a>
            <a href="{{ $siteConfig['hero_btn2_url'] ?? route('flash-sale') }}" class="btn-ghost">
                {{ $siteConfig['hero_btn2_text'] ?? 'Flash Sale' }}
            </a>
        </div>

        {{-- Scroll indicator ── --}}
        <div class="absolute bottom-8 left-10 lg:left-20 xl:left-28 flex items-center gap-3 fade-up fade-up-d4">
            <div class="w-8 h-px bg-white/50"></div>
            <span class="text-[9px] tracking-widest uppercase text-white/60">Cuộn để khám phá</span>
        </div>
    </div>

    {{-- ── Right: hero image ── --}}
    <div class="hero-image-col relative overflow-hidden z-10">
        @if($featuredProduct)
        <img id="hero-img"
             src="{{ $featuredProduct->image_src }}"
             alt="{{ $featuredProduct->name }}"
             class="hero-img absolute inset-0 w-full h-full object-cover">

        {{-- Floating product tag ── --}}
        <div class="absolute bottom-10 left-10 bg-white/90 backdrop-blur-sm px-5 py-4 shadow-sm">
            <p class="text-[9px] tracking-widest uppercase text-bronze mb-1">Nổi bật</p>
            <p class="text-sm font-medium text-ink">{{ $featuredProduct->name }}</p>
            <p class="headline text-lg font-medium text-ink mt-1">
                {{ number_format($featuredProduct->price, 0, ',', '.') }}₫
            </p>
        </div>
        @else
        {{-- Placeholder gradient when no product ── --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(160deg,#F5F1EB 0%,#E8DDD0 100%)">
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="headline text-[120px] text-bronze/10 select-none leading-none">✦</span>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 2  MARQUEE BANNER                                                         --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="border-y border-border py-4 overflow-hidden bg-warm-gray">
    <div class="marquee-track">
        @php $items = ['Giặt Sấy', '·', 'Giao Nhanh', '·', 'Chất Lượng Đảm Bảo', '·', 'Tận Tâm', '·', 'Hoàn Tiền Nếu Không Sạch', '·', 'Hỗ Trợ 24/7', '·']; @endphp
        @foreach(array_merge($items, $items) as $item)
        <span class="text-[11px] tracking-widest uppercase text-ink-muted px-6 whitespace-nowrap">{{ $item }}</span>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 3  USP ROW                                                                --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="max-w-screen-xl mx-auto px-6 lg:px-12 py-24">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-10">
        @foreach([1,2,3,4] as $i)
        @if(!empty($siteConfig["usp{$i}_title"]))
        <div class="text-center" data-reveal data-delay="{{ ($i - 1) * 0.12 }}s">
            <div class="text-3xl mb-5">{{ $siteConfig["usp{$i}_icon"] ?? '' }}</div>
            <h3 class="headline text-base font-medium text-ink mb-2">
                {{ $siteConfig["usp{$i}_title"] ?? '' }}
            </h3>
            <p class="text-[12px] text-ink-muted leading-relaxed">
                {{ $siteConfig["usp{$i}_desc"] ?? '' }}
            </p>
        </div>
        @endif
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 4  FEATURED PRODUCTS                                                      --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-warm-gray py-24">
    <div class="max-w-screen-xl mx-auto px-6 lg:px-12">

        {{-- Section header ── --}}
        <div class="flex flex-col items-center text-center mb-16" data-reveal>
            <p class="section-label mb-4">Tuyển chọn</p>
            <h2 class="headline text-3xl lg:text-4xl font-medium text-ink mb-4">
                Sản phẩm nổi bật
            </h2>
            <p class="text-ink-muted text-sm leading-relaxed max-w-md">
                Được chắt lọc kỹ lưỡng từ những thương hiệu uy tín, đảm bảo
                chất lượng cho từng sợi vải của bạn.
            </p>
            <div class="mt-6 w-12 h-px bg-bronze"></div>
        </div>

        @if($featuredProducts->isEmpty())
        <p class="text-center text-ink-muted py-16 text-sm">Chưa có sản phẩm nào.</p>
        @else

        {{-- Products grid ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            @foreach($featuredProducts as $i => $product)
            @include('components.product-card', [
                'product'  => $product,
                'delay'    => ($i * 0.1) . 's',
            ])
            @endforeach
        </div>

        <div class="text-center mt-16" data-reveal>
            <a href="{{ route('shop') }}" class="btn-ghost">
                Xem toàn bộ sản phẩm
            </a>
        </div>

        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 5  EDITORIAL BANNER — "Why LaundryShop"                                   --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="max-w-screen-xl mx-auto px-6 lg:px-12 py-28">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        {{-- Text block ── --}}
        <div data-reveal>
            <p class="section-label mb-6">Triết lý của chúng tôi</p>
            <h2 class="headline text-3xl lg:text-4xl font-medium text-ink leading-tight mb-7">
                Mỗi sợi vải đều<br>
                <em>xứng đáng được</em><br>
                chăm sóc tinh tế
            </h2>
            <p class="text-ink-muted text-[13px] leading-8 mb-8 max-w-md">
                Chúng tôi hiểu rằng quần áo không chỉ là vải — chúng là kỷ niệm,
                cảm xúc và phong cách sống của bạn. Từng đơn hàng được xử lý bằng
                sự tỉ mỉ và tâm huyết như thể đó là của riêng chúng tôi.
            </p>
            <div class="flex flex-col gap-4">
                @foreach([
                    ['✦', 'Công thức giặt riêng cho từng loại vải'],
                    ['✦', 'Kiểm tra chất lượng 3 lần trước khi giao'],
                    ['✦', 'Bao bì thân thiện môi trường'],
                ] as $point)
                <div class="flex items-start gap-4">
                    <span class="text-bronze text-xs mt-1 flex-shrink-0">{{ $point[0] }}</span>
                    <p class="text-[13px] text-ink-muted">{{ $point[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Image mosaic ── --}}
        <div class="grid grid-cols-2 gap-4" data-reveal data-delay="0.2s">
            @forelse($featuredProducts->take(4) as $i => $p)
            <div class="{{ $i === 0 ? 'aspect-[3/4]' : ($i === 3 ? 'aspect-[3/4]' : 'aspect-square') }}
                        overflow-hidden bg-warm-gray {{ $i === 0 ? 'row-span-2' : '' }}">
                <img src="{{ $p->image_src }}" alt="{{ $p->name }}"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
            </div>
            @empty
            {{-- Placeholder blocks ── --}}
            @foreach([0,1,2,3] as $i)
            <div class="{{ $i === 0 ? 'aspect-[3/4] row-span-2' : 'aspect-square' }} bg-warm-gray"></div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- § 6  CONTACT / CTA STRIP                                                    --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-ink py-24 px-6 lg:px-12" id="contact">
    <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

        {{-- Left: contact form ── --}}
        <div data-reveal>
            <p class="section-label text-bronze mb-5">Liên hệ</p>
            <h2 class="headline text-3xl font-medium text-cream mb-3">
                Chúng tôi lắng nghe bạn
            </h2>
            <p class="text-cream/50 text-[13px] leading-relaxed mb-10">
                Hãy để lại thông tin và chúng tôi sẽ liên hệ lại trong vòng 2 giờ.
            </p>

            <form method="POST" action="{{ route('contact') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] tracking-widest uppercase text-cream/40 mb-2">Họ tên *</label>
                        <input type="text" name="name" required
                               class="w-full bg-transparent border-b border-cream/20 text-cream text-sm py-2.5
                                      focus:outline-none focus:border-bronze placeholder-cream/20
                                      transition-colors"
                               placeholder="Nguyễn Văn A">
                    </div>
                    <div>
                        <label class="block text-[9px] tracking-widest uppercase text-cream/40 mb-2">Điện thoại *</label>
                        <input type="tel" name="phone" required
                               class="w-full bg-transparent border-b border-cream/20 text-cream text-sm py-2.5
                                      focus:outline-none focus:border-bronze placeholder-cream/20
                                      transition-colors"
                               placeholder="0901 234 567">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-cream/40 mb-2">Email</label>
                    <input type="email" name="email"
                           class="w-full bg-transparent border-b border-cream/20 text-cream text-sm py-2.5
                                  focus:outline-none focus:border-bronze placeholder-cream/20 transition-colors"
                           placeholder="email@example.com">
                </div>
                <div>
                    <label class="block text-[9px] tracking-widest uppercase text-cream/40 mb-2">Nội dung</label>
                    <textarea name="message" rows="3"
                              class="w-full bg-transparent border-b border-cream/20 text-cream text-sm py-2.5
                                     focus:outline-none focus:border-bronze placeholder-cream/20
                                     transition-colors resize-none"
                              placeholder="Bạn cần tư vấn dịch vụ nào?"></textarea>
                </div>
                <button type="submit" class="btn-bronze mt-2">Gửi tin nhắn</button>
            </form>
        </div>

        {{-- Right: contact info ── --}}
        <div class="lg:pl-16 lg:border-l lg:border-cream/10" data-reveal data-delay="0.2s">
            <div class="space-y-10">
                @foreach([
                    ['label' => 'Địa chỉ',      'key' => 'footer_address', 'icon' => '◎'],
                    ['label' => 'Hotline',        'key' => 'footer_phone',   'icon' => '◉'],
                    ['label' => 'Email',          'key' => 'footer_email',   'icon' => '○'],
                    ['label' => 'Giờ làm việc',  'key' => 'footer_hours',   'icon' => '◈'],
                ] as $info)
                @if(!empty($siteConfig[$info['key']]))
                <div>
                    <p class="text-[9px] tracking-widest uppercase text-cream/30 mb-2">
                        {{ $info['icon'] }} &nbsp; {{ $info['label'] }}
                    </p>
                    <p class="text-cream/70 text-sm leading-relaxed">
                        {{ $siteConfig[$info['key']] }}
                    </p>
                </div>
                @endif
                @endforeach
            </div>

            {{-- Ornamental quote ── --}}
            <div class="mt-16 pt-10 border-t border-cream/10">
                <p class="headline text-xl font-medium text-cream/30 italic leading-relaxed">
                    "Chất lượng không phải là ngẫu nhiên —<br>
                    đó là kết quả của sự tỉ mỉ."
                </p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Subtle parallax on hero image ──────────────────────────────────────────
const heroImg = document.getElementById('hero-img');
if (heroImg) {
    window.addEventListener('scroll', () => {
        const y = window.scrollY * 0.25;
        heroImg.style.transform = `translateY(${y}px)`;
    }, { passive: true });
}
</script>
@endpush