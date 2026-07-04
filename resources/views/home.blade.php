<x-layouts.public>
    @if ($lede)
        {{-- TOP: hero utama + rail terbaru --}}
        <section class="grid gap-8 lg:grid-cols-12">
            {{-- Hero --}}
            <article class="group reveal lg:col-span-8">
                <a href="{{ route('articles.show', $lede) }}" class="card-media block aspect-[16/9] rounded" tabindex="-1" aria-hidden="true">
                    @if ($lede->thumbnail)
                        <img src="{{ $lede->thumbnailUrl() }}" alt="{{ $lede->title }}" class="h-full w-full object-cover">
                    @else
                        <span class="flex h-full w-full items-center justify-center" style="background-color: {{ $lede->category->color }}14">
                            <span class="kicker text-base" style="color: {{ $lede->category->color }}">{{ $lede->category->name }}</span>
                        </span>
                    @endif
                </a>
                <div class="pt-4">
                    <div class="mb-2 flex items-center gap-2">
                        <x-category-badge :category="$lede->category" class="!text-sm" />
                        <span class="meta" aria-hidden="true">·</span>
                        <time class="meta">{{ $lede->published_at->diffForHumans() }}</time>
                    </div>
                    <h1 class="headline text-4xl sm:text-5xl">
                        <a href="{{ route('articles.show', $lede) }}" class="headline-link">{{ $lede->title }}</a>
                    </h1>
                    @if ($lede->excerpt)
                        <p class="dek mt-3 max-w-2xl">{{ $lede->excerpt }}</p>
                    @endif
                    <p class="meta mt-3">Oleh <span class="font-medium text-ink">{{ $lede->author->name }}</span></p>
                </div>
            </article>

            {{-- Rail terbaru --}}
            <aside class="reveal lg:col-span-4 lg:border-l lg:border-line lg:pl-8">
                <h2 class="section-title mb-4 flex items-center gap-2 border-b-2 border-line-strong pb-2 text-lg">
                    <span class="h-2 w-2 rounded-full bg-signal"></span> Terbaru
                </h2>
                <ol class="divide-y divide-line">
                    @forelse ($terbaru as $i => $item)
                        <li class="group flex gap-3 py-3.5 first:pt-0">
                            <span class="rail-index pt-0.5">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="min-w-0">
                                <div class="mb-1 flex items-center gap-2">
                                    <x-category-badge :category="$item->category" class="!text-[0.65rem]" />
                                    <time class="meta">{{ $item->published_at->diffForHumans() }}</time>
                                </div>
                                <h3 class="headline text-[1.05rem]">
                                    <a href="{{ route('articles.show', $item) }}" class="headline-link">{{ $item->title }}</a>
                                </h3>
                            </div>
                        </li>
                    @empty
                        <li class="py-3 text-sm text-muted">Belum ada berita lain.</li>
                    @endforelse
                </ol>
            </aside>
        </section>

        {{-- SEKSI PER KATEGORI --}}
        @foreach ($sections as $section)
            <section class="mt-14">
                <x-section-header :title="$section->name" :color="$section->color" :href="route('categories.show', $section)" />
                <div class="grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($section->articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
            </section>
        @endforeach
    @else
        <div class="rounded-lg border border-dashed border-line bg-paper py-24 text-center">
            <p class="headline text-2xl">Belum ada berita.</p>
            <p class="mt-2 text-muted">Redaksi sedang menyiapkan kabar terbaru untuk Anda.</p>
        </div>
    @endif
</x-layouts.public>
