<x-layouts.admin title="Pengguna" heading="Manajemen Pengguna" subheading="Kelola akun ketua & admin">
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-muted">{{ $users->total() }} pengguna</p>
        <a href="{{ route('admin.users.create') }}" class="rounded-md bg-action px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand">Tambah pengguna</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-line bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-surface text-muted">
                <tr>
                    <th class="px-4 py-3 font-semibold">Nama</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Peran</th>
                    <th class="px-4 py-3 font-semibold">Artikel</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-muted">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $user->isKetua() ? 'bg-brand/10 text-brand' : 'bg-line text-ink' }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $user->articles_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-action hover:underline">Sunting</a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-signal hover:underline">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-muted">(Anda)</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</x-layouts.admin>
