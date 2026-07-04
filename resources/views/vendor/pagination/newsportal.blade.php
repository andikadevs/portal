@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex items-center justify-center gap-1 text-sm">
        {{-- Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="cursor-not-allowed rounded-md border border-line px-3 py-1.5 text-muted/50">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-md border border-line px-3 py-1.5 transition-colors hover:border-action hover:text-action">Sebelumnya</a>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-disabled="true" class="px-2 py-1.5 text-muted">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="rounded-md bg-ink px-3 py-1.5 font-semibold text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-md border border-line px-3 py-1.5 transition-colors hover:border-action hover:text-action">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Berikutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-md border border-line px-3 py-1.5 transition-colors hover:border-action hover:text-action">Berikutnya</a>
        @else
            <span aria-disabled="true" class="cursor-not-allowed rounded-md border border-line px-3 py-1.5 text-muted/50">Berikutnya</span>
        @endif
    </nav>
@endif
