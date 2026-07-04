@props(['label', 'value', 'color' => '#14315E'])

{{-- Kartu statistik: angka besar + garis kiri berwarna (PRD §7.5). --}}
<div {{ $attributes->merge(['class' => 'reveal rounded-lg border border-line bg-paper p-5']) }} style="border-left: 4px solid {{ $color }}">
    <p class="kicker text-muted">{{ $label }}</p>
    <p class="section-title mt-1.5 text-3xl">{{ $value }}</p>
</div>
