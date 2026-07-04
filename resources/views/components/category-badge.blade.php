@props(['category'])

{{-- Label kategori dengan kode warna rubrik (PRD §7.2/§7.5). --}}
<a
    href="{{ route('categories.show', $category) }}"
    {{ $attributes->merge(['class' => 'kicker inline-flex items-center gap-1.5 transition-opacity hover:opacity-70']) }}
    style="color: {{ $category->color }}"
>
    {{ $category->name }}
</a>
