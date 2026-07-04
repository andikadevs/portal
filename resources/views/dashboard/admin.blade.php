<x-layouts.admin title="Dasbor" heading="Dasbor Admin" :subheading="'Halo, '.auth()->user()->name">
    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Artikel Saya" :value="$stats['my_articles']" color="#2E5BFF" />
        <x-stat-card label="Total Kategori" :value="$stats['categories']" color="#1B9C5D" />
        <x-stat-card label="Komentar di Artikel Saya" :value="$stats['my_comments']" color="#C8443B" />
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.articles.create') }}" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">Tulis artikel</a>
        <a href="{{ route('admin.categories.index') }}" class="rounded-md border border-line bg-white px-4 py-2 text-sm font-semibold transition-colors hover:border-action">Kelola kategori</a>
    </div>

    <section class="mt-8">
        <h2 class="mb-3 font-[family-name:var(--font-display)] text-lg font-bold">Artikel terbaru saya</h2>
        <div class="overflow-hidden rounded-xl border border-line bg-white">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-line bg-paper text-muted">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Judul</th>
                        <th class="px-4 py-3 font-semibold">Kategori</th>
                        <th class="px-4 py-3 font-semibold">Terbit</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($recentArticles as $article)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $article->title }}</td>
                            <td class="px-4 py-3">
                                <span class="kicker" style="color: {{ $article->category->color }}">{{ $article->category->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ optional($article->published_at)->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="text-action hover:underline">Sunting</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-muted">Belum ada artikel. Tambah artikel pertamamu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.admin>
