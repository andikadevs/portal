@props(['article'])

{{-- Kartu berita: thumbnail 16:9, label kategori berwarna, judul Archivo (PRD §7.5). --}}
<article {{ $attributes->merge(['class' => 'group reveal flex flex-col']) }}>
    <a href="{{ route('articles.show', $article) }}" class="block overflow-hidden rounded-lg border border-line bg-white">
        <div class="aspect-video overflow-hidden bg-line">
            @if ($article->thumbnail)
                <img
                    src="{{ Storage::url($article->thumbnail) }}"
                    alt="{{ $article->title }}"
                    loading="lazy"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                >
            @else
                <div class="flex h-full w-full items-center justify-center" style="background-color: {{ $article->category->color }}1a">
                    <span class="kicker" style="color: {{ $article->category->color }}">{{ $article->category->name }}</span>
                </div>
            @endif
        </div>
    </a>

    <div class="flex flex-1 flex-col pt-3">
        <div class="mb-2 flex items-center gap-2 text-muted">
            <x-category-badge :category="$article->category" />
            <span aria-hidden="true">·</span>
            <time class="kicker text-muted" datetime="{{ optional($article->published_at)->toIso8601String() }}">
                {{ optional($article->published_at)->diffForHumans() }}
            </time>
        </div>

        <h3 class="font-[family-name:var(--font-display)] text-lg font-bold leading-snug">
            <a href="{{ route('articles.show', $article) }}" class="transition-colors hover:text-brand">
                {{ $article->title }}
            </a>
        </h3>

        @if ($article->excerpt)
            <p class="mt-2 line-clamp-3 text-sm text-muted">{{ $article->excerpt }}</p>
        @endif

        <p class="mt-3 text-xs text-muted">Oleh {{ $article->author->name }}</p>
    </div>
</article>
