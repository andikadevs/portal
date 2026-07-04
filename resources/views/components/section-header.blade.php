@props(['title', 'color' => '#17191e', 'href' => null, 'linkLabel' => 'Lihat semua'])

{{-- Judul seksi editorial dengan rule tebal berwarna (gaya BBC/NYT). --}}
<div class="mb-5 flex items-end justify-between gap-4 border-b-2 border-line-strong pb-2">
    <h2 class="section-title flex items-center gap-2.5 text-xl sm:text-2xl">
        <span class="inline-block h-5 w-1.5 rounded-sm" style="background-color: {{ $color }}"></span>
        {{ $title }}
    </h2>
    @if ($href)
        <a href="{{ $href }}" class="kicker shrink-0 text-action hover:underline">{{ $linkLabel }} →</a>
    @endif
</div>
