<x-layouts.public :title="$article->title" :description="$article->excerpt">
    <article class="mx-auto max-w-3xl">
        {{-- Kicker: kategori • tanggal --}}
        <div class="mb-3 flex flex-wrap items-center gap-2 text-muted">
            <x-category-badge :category="$article->category" />
            <span aria-hidden="true">·</span>
            <time class="kicker text-muted" datetime="{{ $article->published_at->toIso8601String() }}">
                {{ $article->published_at->translatedFormat('d F Y, H:i') }}
            </time>
        </div>

        <h1 class="font-[family-name:var(--font-display)] text-3xl font-extrabold leading-tight sm:text-4xl">{{ $article->title }}</h1>

        {{-- Byline --}}
        <div class="mt-4 flex items-center gap-3 border-y border-line py-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand text-sm font-bold text-white">
                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($article->author->name, 0, 1)) }}
            </span>
            <div>
                <p class="text-sm font-semibold">{{ $article->author->name }}</p>
                <p class="kicker text-muted">Redaksi NewsPortal</p>
            </div>
        </div>

        {{-- Thumbnail --}}
        @if ($article->thumbnail)
            <figure class="mt-6">
                <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full rounded-xl border border-line object-cover">
            </figure>
        @endif

        {{-- Isi artikel (HTML sudah disanitasi saat disimpan) --}}
        <div class="prose-article mt-8">
            {!! $article->body !!}
        </div>
    </article>

    {{-- KOMENTAR --}}
    <section id="komentar" class="mx-auto mt-14 max-w-3xl border-t border-line pt-8">
        <h2 class="font-[family-name:var(--font-display)] text-xl font-bold">
            Komentar <span class="text-muted">({{ $article->comments->count() }})</span>
        </h2>

        {{-- Form komentar --}}
        <form action="{{ route('comments.store', $article) }}" method="POST" class="mt-5 rounded-xl border border-line bg-white p-5">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium">Nama</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('name') border-signal @enderror">
                    @error('name') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('email') border-signal @enderror">
                    @error('email') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-4">
                <label for="body" class="mb-1 block text-sm font-medium">Komentar</label>
                <textarea id="body" name="body" rows="3" required class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('body') border-signal @enderror">{{ old('body') }}</textarea>
                @error('body') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="mt-4 rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">Kirim komentar</button>
        </form>

        {{-- Daftar komentar --}}
        <div class="mt-6 space-y-4">
            @forelse ($article->comments as $comment)
                <div class="rounded-lg border border-line bg-white p-4">
                    <div class="mb-1 flex items-center justify-between">
                        <p class="text-sm font-semibold">{{ $comment->name }}</p>
                        <time class="kicker text-muted">{{ $comment->created_at->diffForHumans() }}</time>
                    </div>
                    <p class="text-sm text-ink/80">{{ $comment->body }}</p>
                </div>
            @empty
                <p class="rounded-lg border border-dashed border-line bg-white p-6 text-center text-sm text-muted">Belum ada komentar. Jadilah yang pertama berdiskusi.</p>
            @endforelse
        </div>
    </section>

    {{-- Artikel terkait --}}
    @if ($related->isNotEmpty())
        <section class="mx-auto mt-14 max-w-5xl border-t border-line pt-8">
            <h2 class="mb-4 font-[family-name:var(--font-display)] text-xl font-bold">Berita terkait</h2>
            <div class="grid gap-6 sm:grid-cols-3">
                @foreach ($related as $item)
                    <x-article-card :article="$item" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.public>
