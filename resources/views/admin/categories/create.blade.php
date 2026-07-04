<x-layouts.admin title="Tambah Kategori" heading="Tambah kategori" subheading="Buat rubrik berita baru">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @include('admin.categories._form', ['submitLabel' => 'Simpan kategori'])
    </form>
</x-layouts.admin>
