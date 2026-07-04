<x-layouts.public>
    @if ($lede)
        {{-- LEDE + rail TERBARU (PRD §7.4) --}}
        <section class="grid gap-8 lg:grid-cols-3">
            {{-- Hero --}}
            <article class="reveal lg:col-span-2">
                <a href="{{ route('articles.show', $lede) }}" class="group block overflow-hidden rounded-xl border border-line bg-white">
                    <div class="aspect-video overflow-hidden bg-line">
                        @if ($lede->thumbnail)
                            <img src="{{ $lede->thumbnailUrl() }}" alt="{{ $lede->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center" style="background-color: {{ $lede->category->color }}1a">
                                <span class="kicker text-lg" style="color: {{ $lede->category->color }}">{{ $lede->category->name }}</span>
                            </div>
                        @endif
                    </div>
                </a>
                <div class="pt-4">
                    <div class="mb-2 flex items-center gap-2 text-muted">
                        <x-category-badge :category="$lede->category" />
                        <span aria-hidden="true">·</span>
                        <time class="kicker text-muted">{{ $lede->published_at->diffForHumans() }}</time>
                    </div>
                    <h1 class="font-[family-name:var(--font-display)] text-3xl font-extrabold leading-tight sm:text-4xl">
                        <a href="{{ route('articles.show', $lede) }}" class="transition-colors hover:text-brand">{{ $lede->title }}</a>
                    </h1>
                    @if ($lede->excerpt)
                        <p class="mt-3 max-w-2xl font-[family-name:var(--font-serif)] text-lg text-ink/80">{{ $lede->excerpt }}</p>
                    @endif
                    <p class="mt-3 text-sm text-muted">Oleh {{ $lede->author->name }}</p>
                </div>
            </article>

            {{-- Rail terbaru --}}
            <aside class="reveal">
                <h2 class="kicker mb-3 border-b border-line pb-2 text-signal">Terbaru</h2>
                <div class="divide-y divide-line">
                    @forelse ($terbaru as $item)
                        <article class="flex gap-3 py-3">
                            <div class="min-w-0">
                                <div class="mb-1 flex items-center gap-2 text-muted">
                                    <x-category-badge :category="$item->category" class="!text-[0.68rem]" />
                                    <span aria-hidden="true">·</span>
                                    <time class="kicker text-muted">{{ $item->published_at->diffForHumans() }}</time>
                                </div>
                                <h3 class="font-[family-name:var(--font-display)] text-base font-bold leading-snug">
                                    <a href="{{ route('articles.show', $item) }}" class="transition-colors hover:text-brand">{{ $item->title }}</a>
                                </h3>
                            </div>
                        </article>
                    @empty
                        <p class="py-3 text-sm text-muted">Belum ada berita lain.</p>
                    @endforelse
                </div>
            </aside>
        </section>

        {{-- SEKSI PER KATEGORI --}}
        @foreach ($sections as $section)
            <section class="mt-14">
                <div class="mb-4 flex items-baseline justify-between border-b border-line pb-2">
                    <h2 class="flex items-center gap-2 font-[family-name:var(--font-display)] text-xl font-bold">
                        <span class="inline-block h-3 w-1 rounded-sm" style="background-color: {{ $section->color }}"></span>
                        {{ $section->name }}
                    </h2>
                    <a href="{{ route('categories.show', $section) }}" class="kicker text-action hover:underline">Lihat semua</a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($section->articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
            </section>
        @endforeach
    @else
        <div class="rounded-xl border border-dashed border-line bg-white py-20 text-center">
            <p class="font-[family-name:var(--font-display)] text-xl font-bold">Belum ada berita.</p>
            <p class="mt-2 text-muted">Redaksi sedang menyiapkan kabar terbaru untuk Anda.</p>
        </div>
    @endif
</x-layouts.public>
