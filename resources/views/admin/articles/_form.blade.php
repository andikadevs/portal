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

        <div class="rounded-xl border border-line bg-white p-4">
            <label for="thumbnail" class="mb-1 block text-sm font-medium">Thumbnail</label>
            @if ($article->thumbnail)
                <img src="{{ Storage::url($article->thumbnail) }}" alt="" class="mb-2 aspect-video w-full rounded object-cover">
            @endif
            <img id="thumb-preview" class="mb-2 hidden aspect-video w-full rounded object-cover" alt="Pratinjau thumbnail">
            <input id="thumbnail" type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                class="w-full text-sm text-muted file:mr-3 file:rounded-md file:border-0 file:bg-ink file:px-3 file:py-1.5 file:text-white @error('thumbnail') text-signal @enderror"
                onchange="const f=this.files[0]; const p=document.getElementById('thumb-preview'); if(f){p.src=URL.createObjectURL(f);p.classList.remove('hidden');}">
            @error('thumbnail') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-muted">JPG, PNG, atau WEBP. Maks 2 MB.</p>
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
@endpush
