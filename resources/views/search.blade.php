<x-layouts.public title="Pencarian">
    <header class="mb-8 border-b-2 border-line-strong pb-5">
        <p class="kicker text-signal">Pencarian</p>
        <h1 class="section-title mt-1 text-3xl sm:text-4xl">Cari berita</h1>
        <form action="{{ route('search') }}" method="GET" class="mt-4 flex max-w-xl" role="search">
            <input type="search" name="q" value="{{ $q }}" placeholder="Ketik judul atau kata kunci…" autofocus class="w-full rounded-l-md border border-line bg-paper px-3 py-2.5 text-sm focus:border-action focus:outline-none">
            <button type="submit" class="rounded-r-md border border-ink bg-ink px-5 py-2.5 text-sm font-medium text-white hover:bg-brand">Cari</button>
        </form>
    </header>

    @if ($q === '')
        <p class="text-muted">Masukkan kata kunci untuk mencari berita di seluruh rubrik.</p>
    @elseif ($articles->isNotEmpty())
        <p class="mb-6 text-sm text-muted">Hasil untuk “<span class="font-semibold text-ink">{{ $q }}</span>” — {{ $articles->total() }} artikel.</p>
        <div class="grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>
        <div class="mt-12">{{ $articles->links() }}</div>
    @else
        <div class="rounded-lg border border-dashed border-line bg-paper py-16 text-center">
            <p class="headline text-xl">Tidak ada hasil untuk “{{ $q }}”.</p>
            <p class="mt-2 text-muted">Coba kata kunci yang lebih umum.</p>
        </div>
    @endif
</x-layouts.public>
