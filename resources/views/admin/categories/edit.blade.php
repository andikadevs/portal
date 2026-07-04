<x-layouts.admin title="Sunting Kategori" heading="Sunting kategori" :subheading="$category->name">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @method('PUT')
        @include('admin.categories._form', ['submitLabel' => 'Perbarui kategori'])
    </form>
</x-layouts.admin>
