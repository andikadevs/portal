<x-layouts.admin title="Tambah Pengguna" heading="Tambah pengguna" subheading="Buat akun ketua atau admin">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @include('admin.users._form', ['submitLabel' => 'Simpan pengguna'])
    </form>
</x-layouts.admin>
