<x-layouts.public :title="$article->title" :description="$article->excerpt">
    @php
        $words = str_word_count(strip_tags($article->body));
        $readingMinutes = max(1, (int) ceil($words / 200));
    @endphp

    <article class="mx-auto max-w-3xl">
        {{-- Kicker --}}
        <div class="mb-3">
            <x-category-badge :category="$article->category" class="!text-sm" />
        </div>

        <h1 class="headline text-4xl sm:text-[2.75rem]">{{ $article->title }}</h1>

        @if ($article->excerpt)
            <p class="dek mt-4 text-xl">{{ $article->excerpt }}</p>
        @endif

        {{-- Byline --}}
        <div class="mt-6 flex items-center gap-3 border-y border-line py-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand text-sm font-bold text-white">
                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($article->author->name, 0, 1)) }}
            </span>
            <div class="text-sm">
                <p class="font-semibold">Oleh {{ $article->author->name }}</p>
                <p class="meta">
                    <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->translatedFormat('d F Y, H:i') }}</time>
                    <span aria-hidden="true">·</span> {{ $readingMinutes }} menit baca
                </p>
            </div>
        </div>

        {{-- Thumbnail --}}
        @if ($article->thumbnail)
            <figure class="mt-6">
                <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->title }}" class="w-full rounded object-cover">
                <figcaption class="meta mt-2 border-l-2 border-line pl-2">{{ $article->category->name }} · NewsPortal</figcaption>
            </figure>
        @endif

        {{-- Isi (HTML sudah disanitasi saat disimpan) --}}
        <div class="prose-article mt-8">
            {!! $article->body !!}
        </div>

        {{-- Bagikan / tag --}}
        <div class="mt-8 flex flex-wrap items-center gap-2 border-t border-line pt-5">
            <span class="kicker text-muted">Rubrik</span>
            <a href="{{ route('categories.show', $article->category) }}" class="rounded-full border border-line px-3 py-1 text-sm font-medium hover:border-action hover:text-action">{{ $article->category->name }}</a>
        </div>
    </article>

    {{-- KOMENTAR --}}
    <section id="komentar" class="mx-auto mt-14 max-w-3xl border-t-2 border-line-strong pt-8">
        <h2 class="section-title text-xl">Komentar <span class="text-muted">({{ $article->comments->count() }})</span></h2>

        <form action="{{ route('comments.store', $article) }}" method="POST" class="mt-5 rounded-lg border border-line bg-paper p-5">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium">Nama</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-line bg-surface px-3 py-2 text-sm focus:border-action focus:bg-paper focus:outline-none @error('name') border-signal @enderror">
                    @error('name') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border border-line bg-surface px-3 py-2 text-sm focus:border-action focus:bg-paper focus:outline-none @error('email') border-signal @enderror">
                    @error('email') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-4">
                <label for="body" class="mb-1 block text-sm font-medium">Komentar</label>
                <textarea id="body" name="body" rows="3" required class="w-full rounded-md border border-line bg-surface px-3 py-2 text-sm focus:border-action focus:bg-paper focus:outline-none @error('body') border-signal @enderror">{{ old('body') }}</textarea>
                @error('body') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="mt-4 rounded-md bg-action px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand">Kirim komentar</button>
        </form>

        <div class="mt-6 space-y-3">
            @forelse ($article->comments as $comment)
                <div class="rounded-lg border border-line bg-paper p-4">
                    <div class="mb-1.5 flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-surface text-xs font-bold text-brand">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($comment->name, 0, 1)) }}</span>
                        <p class="text-sm font-semibold">{{ $comment->name }}</p>
                        <time class="meta ml-auto">{{ $comment->created_at->diffForHumans() }}</time>
                    </div>
                    <p class="pl-9 text-sm leading-relaxed text-ink/80">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="rounded-lg border border-dashed border-line bg-paper p-6 text-center text-sm text-muted">Belum ada komentar. Jadilah yang pertama berdiskusi.</p>
            @endforelse
        </div>
    </section>

    {{-- Terkait --}}
    @if ($related->isNotEmpty())
        <section class="mx-auto mt-14 max-w-5xl">
            <x-section-header title="Berita terkait" :color="$article->category->color" />
            <div class="grid gap-x-6 gap-y-8 sm:grid-cols-3">
                @foreach ($related as $item)
                    <x-article-card :article="$item" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.public>
