<x-layouts.public :title="$category->name">
    <header class="mb-8 border-b-2 border-line-strong pb-5">
        <div class="flex items-center gap-2.5">
            <span class="inline-block h-6 w-2 rounded-sm" style="background-color: {{ $category->color }}"></span>
            <h1 class="section-title text-3xl sm:text-4xl">{{ $category->name }}</h1>
        </div>
        <p class="dek mt-2">Kabar terbaru seputar {{ Str::lower($category->name) }}.</p>

        <form action="{{ route('categories.show', $category) }}" method="GET" class="mt-4 flex max-w-md" role="search">
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari dalam {{ $category->name }}…" class="w-full rounded-l-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none">
            <button type="submit" class="rounded-r-md border border-ink bg-ink px-4 py-2 text-sm font-medium text-white hover:bg-brand">Cari</button>
        </form>
    </header>

    @if ($q !== '')
        <p class="mb-6 text-sm text-muted">Hasil untuk “<span class="font-semibold text-ink">{{ $q }}</span>” — {{ $articles->total() }} artikel.</p>
    @endif

    @if ($articles->isNotEmpty())
        <div class="grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>
        <div class="mt-12">{{ $articles->links() }}</div>
    @else
        <div class="rounded-lg border border-dashed border-line bg-paper py-16 text-center">
            <p class="headline text-xl">Belum ada artikel.</p>
            <p class="mt-2 text-muted">{{ $q !== '' ? 'Coba kata kunci lain.' : 'Nantikan kabar terbaru di kategori ini.' }}</p>
        </div>
    @endif
</x-layouts.public>
