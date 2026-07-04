<x-layouts.admin title="Artikel" heading="Artikel" subheading="Kelola konten berita">
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-muted">{{ $articles->total() }} artikel</p>
        <a href="{{ route('admin.articles.create') }}" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">Tulis artikel</a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="border-b border-line bg-paper text-muted">
                <tr>
                    <th class="px-4 py-3 font-semibold">Judul</th>
                    <th class="px-4 py-3 font-semibold">Kategori</th>
                    <th class="px-4 py-3 font-semibold">Penulis</th>
                    <th class="px-4 py-3 font-semibold">Terbit</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            <div class="flex items-center gap-3">
                                @if ($article->thumbnail)
                                    <img src="{{ $article->thumbnailUrl() }}" alt="" class="h-10 w-16 rounded object-cover">
                                @else
                                    <span class="flex h-10 w-16 items-center justify-center rounded text-[0.6rem] text-white" style="background-color: {{ $article->category->color }}">Tanpa gambar</span>
                                @endif
                                <span class="line-clamp-2">{{ $article->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3"><span class="kicker" style="color: {{ $article->category->color }}">{{ $article->category->name }}</span></td>
                        <td class="px-4 py-3 text-muted">{{ $article->author->name }}</td>
                        <td class="px-4 py-3 text-muted">{{ optional($article->published_at)->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('articles.show', $article) }}" target="_blank" class="text-muted hover:text-ink">Lihat</a>
                                <a href="{{ route('admin.articles.edit', $article) }}" class="text-action hover:underline">Sunting</a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-signal hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-muted">Belum ada artikel. Tambah artikel pertamamu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $articles->links() }}</div>
</x-layouts.admin>
