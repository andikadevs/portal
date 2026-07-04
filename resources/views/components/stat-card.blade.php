@props(['label', 'value', 'color' => '#14315E'])

{{-- Kartu statistik: angka besar Archivo + garis kiri berwarna (PRD §7.5). --}}
<div {{ $attributes->merge(['class' => 'reveal rounded-xl border border-line bg-white p-5']) }} style="border-left: 4px solid {{ $color }}">
    <p class="kicker text-muted">{{ $label }}</p>
    <p class="mt-1 font-[family-name:var(--font-display)] text-3xl font-extrabold">{{ $value }}</p>
</div>
