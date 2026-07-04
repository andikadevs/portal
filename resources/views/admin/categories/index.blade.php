<x-layouts.admin title="Kategori" heading="Kategori" subheading="Kelola rubrik berita">
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-muted">{{ $categories->total() }} kategori</p>
        <a href="{{ route('admin.categories.create') }}" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">Tambah kategori</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-line bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-surface text-muted">
                <tr>
                    <th class="px-4 py-3 font-semibold">Nama</th>
                    <th class="px-4 py-3 font-semibold">Slug</th>
                    <th class="px-4 py-3 font-semibold">Warna</th>
                    <th class="px-4 py-3 font-semibold">Artikel</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-muted">{{ $category->slug }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="inline-block h-4 w-4 rounded-full border border-line" style="background-color: {{ $category->color }}"></span>
                                <code class="text-xs text-muted">{{ $category->color }}</code>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $category->articles_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-action hover:underline">Sunting</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-signal hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-muted">Belum ada kategori. Tambah kategori pertamamu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $categories->links() }}</div>
</x-layouts.admin>
