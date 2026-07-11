@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1.5">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-7 h-7 flex items-center justify-center rounded-full text-gray-300 cursor-not-allowed border border-transparent" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-500 border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-colors" aria-label="@lang('pagination.previous')">
                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-7 h-7 flex items-center justify-center text-gray-400 text-[11px] font-medium" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-7 h-7 flex items-center justify-center rounded-full bg-primary text-white text-[11px] font-bold shadow-sm" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-600 border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-colors text-[11px] font-medium" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-500 border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-colors" aria-label="@lang('pagination.next')">
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
        @else
            <span class="w-7 h-7 flex items-center justify-center rounded-full text-gray-300 cursor-not-allowed border border-transparent" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
