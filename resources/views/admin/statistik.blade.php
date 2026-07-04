<x-layouts.admin title="Statistik" heading="Statistik" subheading="Ringkasan data portal">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total User" :value="$stats['users']" color="#14315E" />
        <x-stat-card label="Total Kategori" :value="$stats['categories']" color="#1B9C5D" />
        <x-stat-card label="Total Artikel" :value="$stats['articles']" color="#2E5BFF" />
        <x-stat-card label="Total Komentar" :value="$stats['comments']" color="#C8443B" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        {{-- Distribusi artikel per kategori --}}
        <section class="rounded-xl border border-line bg-white p-5">
            <h2 class="mb-4 font-[family-name:var(--font-display)] text-lg font-bold">Artikel per kategori</h2>
            <div class="space-y-3">
                @foreach ($perCategory as $category)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2">
                                <span class="inline-block h-2.5 w-2.5 rounded-full" style="background-color: {{ $category->color }}"></span>
                                {{ $category->name }}
                            </span>
                            <span class="font-semibold">{{ $category->articles_count }}</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-line">
                            <div class="h-full rounded-full" style="width: {{ round($category->articles_count / $maxCount * 100) }}%; background-color: {{ $category->color }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Kontribusi per penulis --}}
        <section class="rounded-xl border border-line bg-white p-5">
            <h2 class="mb-4 font-[family-name:var(--font-display)] text-lg font-bold">Kontribusi penulis</h2>
            <div class="overflow-hidden rounded-lg border border-line">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-line bg-paper text-muted">
                        <tr><th class="px-4 py-2 font-semibold">Penulis</th><th class="px-4 py-2 font-semibold">Peran</th><th class="px-4 py-2 text-right font-semibold">Artikel</th></tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($perAuthor as $author)
                            <tr>
                                <td class="px-4 py-2 font-medium">{{ $author->name }}</td>
                                <td class="px-4 py-2 text-muted">{{ ucfirst($author->role) }}</td>
                                <td class="px-4 py-2 text-right font-semibold">{{ $author->articles_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
