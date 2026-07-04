@csrf
<div class="max-w-lg space-y-5">
    <div>
        <label for="name" class="mb-1 block text-sm font-medium">Nama kategori</label>
        <input id="name" name="name" value="{{ old('name', $category->name) }}" required
            class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('name') border-signal @enderror">
        @error('name') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="color" class="mb-1 block text-sm font-medium">Warna rubrik</label>
        <div class="flex items-center gap-3">
            <input id="color" name="color" type="color" value="{{ old('color', $category->color) }}"
                class="h-10 w-14 cursor-pointer rounded border border-line bg-white p-1"
                oninput="document.getElementById('color-hex').value = this.value">
            <input id="color-hex" type="text" value="{{ old('color', $category->color) }}" readonly
                class="w-32 rounded-md border border-line bg-paper px-3 py-2 text-sm text-muted">
        </div>
        @error('color') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-muted">Warna ini menandai kategori di seluruh situs.</p>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">{{ $submitLabel }}</button>
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-muted hover:text-ink">Batal</a>
    </div>
</div>
