@csrf
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Kolom utama --}}
    <div class="space-y-5 lg:col-span-2">
        <div>
            <label for="title" class="mb-1 block text-sm font-medium">Judul</label>
            <input id="title" name="title" value="{{ old('title', $article->title) }}" required
                class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('title') border-signal @enderror">
            @error('title') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="excerpt" class="mb-1 block text-sm font-medium">Ringkasan <span class="font-normal text-muted">(opsional)</span></label>
            <textarea id="excerpt" name="excerpt" rows="2" maxlength="280"
                class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('excerpt') border-signal @enderror">{{ old('excerpt', $article->excerpt) }}</textarea>
            @error('excerpt') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="body" class="mb-1 block text-sm font-medium">Isi artikel</label>
            <textarea id="body" name="body" rows="12"
                class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('body') border-signal @enderror">{{ old('body', $article->body) }}</textarea>
            @error('body') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">
        <div class="rounded-xl border border-line bg-white p-4">
            <label for="category_id" class="mb-1 block text-sm font-medium">Kategori</label>
            <select id="category_id" name="category_id" required
                class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('category_id') border-signal @enderror">
                <option value="">— Pilih kategori —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-xl border border-line bg-white p-4">
            <label for="published_at" class="mb-1 block text-sm font-medium">Waktu terbit</label>
            <input id="published_at" type="datetime-local" name="published_at"
                value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none">
            <p class="mt-1 text-xs text-muted">Kosongkan untuk terbit sekarang.</p>
        </div>

        @php $pexelsEnabled = filled(config('services.pexels.key')); @endphp
        <div class="rounded-xl border border-line bg-white p-4" data-pexels-search-url="{{ route('admin.pexels.search') }}">
            <label for="thumbnail" class="mb-1 block text-sm font-medium">Thumbnail</label>
            @if ($article->thumbnail)
                <img src="{{ $article->thumbnailUrl() }}" alt="" class="mb-2 aspect-video w-full rounded object-cover">
            @endif
            <img id="thumb-preview" class="mb-2 hidden aspect-video w-full rounded object-cover" alt="Pratinjau thumbnail">

            {{-- Nilai dari pemilih Pexels (URL eksternal). --}}
            <input type="hidden" id="thumbnail_url" name="thumbnail_url" value="{{ old('thumbnail_url') }}">

            <input id="thumbnail" type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                class="w-full text-sm text-muted file:mr-3 file:rounded-md file:border-0 file:bg-ink file:px-3 file:py-1.5 file:text-white @error('thumbnail') text-signal @enderror"
                onchange="const f=this.files[0]; const p=document.getElementById('thumb-preview'); if(f){p.src=URL.createObjectURL(f);p.classList.remove('hidden');document.getElementById('thumbnail_url').value='';}">
            @error('thumbnail') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
            @error('thumbnail_url') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-muted">JPG, PNG, atau WEBP. Maks 2 MB.</p>

            {{-- Pemilih Pexels --}}
            <div class="mt-3 border-t border-line pt-3">
                @if ($pexelsEnabled)
                    <button type="button" data-pexels-toggle class="w-full rounded-md border border-line px-3 py-1.5 text-sm font-medium transition-colors hover:border-action hover:text-action">
                        Pilih dari Pexels
                    </button>
                    <div data-pexels-panel class="mt-3 hidden">
                        <div class="flex gap-2">
                            <input type="text" data-pexels-query placeholder="Cari foto, mis. teknologi…" class="w-full rounded-md border border-line bg-paper px-3 py-1.5 text-sm focus:border-action focus:outline-none">
                            <button type="button" data-pexels-go class="rounded-md bg-ink px-3 py-1.5 text-sm text-white hover:bg-brand">Cari</button>
                        </div>
                        <p data-pexels-status class="mt-2 text-xs text-muted"></p>
                        <div data-pexels-results class="mt-2 grid max-h-64 grid-cols-3 gap-2 overflow-y-auto"></div>
                        <p class="mt-2 text-[0.7rem] text-muted">Foto oleh <a href="https://pexels.com" target="_blank" rel="noopener" class="underline">Pexels</a>.</p>
                    </div>
                @else
                    <p class="text-xs text-muted">Pemilih Pexels nonaktif. Setel <code>PEXELS_API_KEY</code> di <code>.env</code> untuk mengaktifkannya. Upload manual tetap berfungsi.</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">{{ $submitLabel }}</button>
            <a href="{{ route('admin.articles.index') }}" class="text-sm text-muted hover:text-ink">Batal</a>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#body'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'],
            })
            .catch((error) => console.error(error));
    </script>

    {{-- Pemilih thumbnail Pexels --}}
    <script>
        (function () {
            const root = document.querySelector('[data-pexels-search-url]');
            const toggle = document.querySelector('[data-pexels-toggle]');
            if (!root || !toggle) return;

            const url = root.dataset.pexelsSearchUrl;
            const panel = root.querySelector('[data-pexels-panel]');
            const queryInput = root.querySelector('[data-pexels-query]');
            const goBtn = root.querySelector('[data-pexels-go]');
            const results = root.querySelector('[data-pexels-results]');
            const status = root.querySelector('[data-pexels-status]');
            const hidden = document.getElementById('thumbnail_url');
            const preview = document.getElementById('thumb-preview');
            const fileInput = document.getElementById('thumbnail');

            toggle.addEventListener('click', () => panel.classList.toggle('hidden'));

            async function search() {
                const q = queryInput.value.trim();
                if (!q) { status.textContent = 'Ketik kata kunci dulu.'; return; }
                status.textContent = 'Mencari…';
                results.innerHTML = '';
                try {
                    const res = await fetch(`${url}?q=${encodeURIComponent(q)}`, { headers: { Accept: 'application/json' } });
                    const data = await res.json();
                    if (!data.enabled) { status.textContent = data.message || 'Pexels nonaktif.'; return; }
                    if (!data.photos.length) { status.textContent = 'Tidak ada hasil.'; return; }
                    status.textContent = `${data.photos.length} foto ditemukan.`;
                    data.photos.forEach((photo) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'group relative aspect-video overflow-hidden rounded border border-line';
                        btn.innerHTML = `<img src="${photo.thumb}" alt="${photo.alt}" class="h-full w-full object-cover">`;
                        btn.addEventListener('click', () => {
                            hidden.value = photo.full;
                            if (fileInput) fileInput.value = '';
                            preview.src = photo.full;
                            preview.classList.remove('hidden');
                            status.textContent = `Terpilih: foto oleh ${photo.photographer}.`;
                            results.querySelectorAll('button').forEach((b) => b.classList.remove('ring-2', 'ring-action'));
                            btn.classList.add('ring-2', 'ring-action');
                        });
                        results.appendChild(btn);
                    });
                } catch (e) {
                    status.textContent = 'Gagal memuat dari Pexels.';
                }
            }

            goBtn.addEventListener('click', search);
            queryInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); search(); } });
        })();
    </script>
@endpush
