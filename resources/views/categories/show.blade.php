<x-layouts.public :title="$category->name">
    <header class="mb-8 border-b border-line pb-4">
        <div class="flex items-center gap-2">
            <span class="inline-block h-4 w-1.5 rounded-sm" style="background-color: {{ $category->color }}"></span>
            <h1 class="font-[family-name:var(--font-display)] text-3xl font-extrabold">{{ $category->name }}</h1>
        </div>
        <p class="mt-2 text-muted">Kabar terbaru seputar {{ Str::lower($category->name) }}.</p>

        {{-- Cari dalam kategori --}}
        <form action="{{ route('categories.show', $category) }}" method="GET" class="mt-4 flex max-w-md" role="search">
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari dalam {{ $category->name }}…" class="w-full rounded-l-md border border-line bg-white px-3 py-2 text-sm focus:border-action focus:outline-none">
            <button type="submit" class="rounded-r-md bg-ink px-4 py-2 text-sm text-white hover:bg-brand">Cari</button>
        </form>
    </header>

    @if ($q !== '')
        <p class="mb-6 text-sm text-muted">Menampilkan hasil untuk “<span class="font-semibold text-ink">{{ $q }}</span>” — {{ $articles->total() }} artikel.</p>
    @endif

    @if ($articles->isNotEmpty())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $articles->links() }}
        </div>
    @else
        <div class="rounded-xl border border-dashed border-line bg-white py-16 text-center">
            <p class="font-[family-name:var(--font-display)] text-lg font-bold">Belum ada artikel.</p>
            <p class="mt-2 text-muted">{{ $q !== '' ? 'Coba kata kunci lain.' : 'Nantikan kabar terbaru di kategori ini.' }}</p>
        </div>
    @endif
</x-layouts.public>
