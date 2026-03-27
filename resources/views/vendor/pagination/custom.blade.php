@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between mt-6">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">‹ Trước</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition">‹ Trước</a>
        @endif

        <div class="hidden sm:flex gap-1">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 text-gray-400">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 text-white rounded-lg" style="background: var(--accent);">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition">Sau ›</a>
        @else
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Sau ›</span>
        @endif
    </nav>

    <div class="flex justify-between sm:hidden mt-4">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg">‹ Trước</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">‹ Trước</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Sau ›</a>
        @else
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg">Sau ›</span>
        @endif
    </div>
@endif