@props(['article', 'feature' => false])

{{-- Kartu berita: media 16:9, kicker kategori berwarna, judul serif (PRD §7.5). --}}
<article {{ $attributes->merge(['class' => 'group reveal flex flex-col']) }}>
    <a href="{{ route('articles.show', $article) }}" class="card-media block aspect-video rounded" tabindex="-1" aria-hidden="true">
        @if ($article->thumbnail)
            <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->title }}" loading="lazy" class="h-full w-full object-cover">
        @else
            <span class="flex h-full w-full items-center justify-center" style="background-color: {{ $article->category->color }}14">
                <span class="kicker" style="color: {{ $article->category->color }}">{{ $article->category->name }}</span>
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col pt-3">
        <div class="mb-1.5 flex items-center gap-2">
            <x-category-badge :category="$article->category" />
            <span class="meta" aria-hidden="true">·</span>
            <time class="meta" datetime="{{ optional($article->published_at)->toIso8601String() }}">{{ optional($article->published_at)->diffForHumans() }}</time>
        </div>

        <h3 class="headline {{ $feature ? 'text-2xl sm:text-3xl' : 'text-xl' }}">
            <a href="{{ route('articles.show', $article) }}" class="headline-link">{{ $article->title }}</a>
        </h3>

        @if ($article->excerpt)
            <p class="mt-2 text-sm leading-relaxed text-muted {{ $feature ? '' : 'line-clamp-3' }}">{{ $article->excerpt }}</p>
        @endif

        <p class="meta mt-2.5">Oleh <span class="font-medium text-ink">{{ $article->author->name }}</span></p>
    </div>
</article>
