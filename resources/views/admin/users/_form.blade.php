@csrf
<div class="max-w-lg space-y-5">
    <div>
        <label for="name" class="mb-1 block text-sm font-medium">Nama</label>
        <input id="name" name="name" value="{{ old('name', $user->name) }}" required
            class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('name') border-signal @enderror">
        @error('name') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="mb-1 block text-sm font-medium">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
            class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('email') border-signal @enderror">
        @error('email') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="role" class="mb-1 block text-sm font-medium">Peran</label>
        <select id="role" name="role" required
            class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('role') border-signal @enderror">
            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin — kelola konten</option>
            <option value="ketua" @selected(old('role', $user->role) === 'ketua')>Ketua — akses penuh</option>
        </select>
        @error('role') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="mb-1 block text-sm font-medium">Kata sandi @if ($user->exists) <span class="font-normal text-muted">(kosongkan jika tak diubah)</span> @endif</label>
        <input id="password" type="password" name="password" autocomplete="new-password" @required(! $user->exists)
            class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('password') border-signal @enderror">
        @error('password') <p class="mt-1 text-xs text-signal">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-1 block text-sm font-medium">Konfirmasi kata sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
            class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none">
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">{{ $submitLabel }}</button>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-muted hover:text-ink">Batal</a>
    </div>
</div>
