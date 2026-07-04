<x-layouts.admin title="Dasbor" heading="Dasbor Ketua" :subheading="'Halo, '.auth()->user()->name">
    {{-- Kartu statistik penuh (PRD F-11) --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total User" :value="$stats['users']" color="#14315E" />
        <x-stat-card label="Total Kategori" :value="$stats['categories']" color="#1B9C5D" />
        <x-stat-card label="Total Artikel" :value="$stats['articles']" color="#2E5BFF" />
        <x-stat-card label="Total Komentar" :value="$stats['comments']" color="#C8443B" />
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.articles.create') }}" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">Tulis artikel</a>
        <a href="{{ route('admin.users.create') }}" class="rounded-md border border-line bg-white px-4 py-2 text-sm font-semibold transition-colors hover:border-action">Tambah pengguna</a>
        <a href="{{ route('admin.statistik') }}" class="rounded-md border border-line bg-white px-4 py-2 text-sm font-semibold transition-colors hover:border-action">Lihat statistik</a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section>
            <h2 class="mb-3 section-title text-lg">Artikel terbaru</h2>
            <div class="overflow-hidden rounded-xl border border-line bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-line bg-surface text-muted">
                        <tr><th class="px-4 py-3 font-semibold">Judul</th><th class="px-4 py-3 font-semibold">Penulis</th></tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($recentArticles as $article)
                            <tr>
                                <td class="px-4 py-3 font-medium">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="hover:text-brand">{{ $article->title }}</a>
                                </td>
                                <td class="px-4 py-3 text-muted">{{ $article->author->name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-8 text-center text-muted">Belum ada artikel.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <h2 class="mb-3 section-title text-lg">Komentar terbaru</h2>
            <div class="space-y-3">
                @forelse ($recentComments as $comment)
                    <div class="rounded-lg border border-line bg-white p-4">
                        <div class="mb-1 flex items-center justify-between">
                            <p class="text-sm font-semibold">{{ $comment->name }}</p>
                            <time class="kicker text-muted">{{ $comment->created_at->diffForHumans() }}</time>
                        </div>
                        <p class="line-clamp-2 text-sm text-ink/80">{{ $comment->body }}</p>
                        @if ($comment->article)
                            <a href="{{ route('articles.show', $comment->article) }}" class="mt-1 inline-block text-xs text-action hover:underline">pada: {{ $comment->article->title }}</a>
                        @endif
                    </div>
                @empty
                    <p class="rounded-lg border border-dashed border-line bg-white p-6 text-center text-sm text-muted">Belum ada komentar.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.admin>
