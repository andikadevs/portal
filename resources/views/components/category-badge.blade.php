@props(['category'])

{{-- Label kategori dengan kode warna rubrik (PRD §7.2/§7.5). --}}
<a
    href="{{ route('categories.show', $category) }}"
    {{ $attributes->merge(['class' => 'kicker inline-flex items-center gap-1.5 text-ink hover:opacity-80 transition-opacity']) }}
>
    <span class="inline-block h-2 w-2 rounded-full" style="background-color: {{ $category->color }}"></span>
    {{ $category->name }}
</a>
