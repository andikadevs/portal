<x-layouts.public title="Pencarian">
    <header class="mb-8 border-b border-line pb-4">
        <h1 class="font-[family-name:var(--font-display)] text-3xl font-extrabold">Pencarian</h1>
        <form action="{{ route('search') }}" method="GET" class="mt-4 flex max-w-xl" role="search">
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul atau isi berita…" autofocus class="w-full rounded-l-md border border-line bg-white px-3 py-2 text-sm focus:border-action focus:outline-none">
            <button type="submit" class="rounded-r-md bg-ink px-4 py-2 text-sm text-white hover:bg-brand">Cari</button>
        </form>
    </header>

    @if ($q === '')
        <p class="text-muted">Ketik kata kunci untuk mencari berita di seluruh kategori.</p>
    @elseif ($articles->isNotEmpty())
        <p class="mb-6 text-sm text-muted">Menampilkan hasil untuk “<span class="font-semibold text-ink">{{ $q }}</span>” — {{ $articles->total() }} artikel.</p>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>
        <div class="mt-10">{{ $articles->links() }}</div>
    @else
        <div class="rounded-xl border border-dashed border-line bg-white py-16 text-center">
            <p class="font-[family-name:var(--font-display)] text-lg font-bold">Tidak ada hasil untuk “{{ $q }}”.</p>
            <p class="mt-2 text-muted">Coba kata kunci yang lebih umum.</p>
        </div>
    @endif
</x-layouts.public>
