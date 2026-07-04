<x-layouts.admin title="Tulis Artikel" heading="Tulis artikel" subheading="Buat berita baru">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.articles._form', ['submitLabel' => 'Publikasikan'])
    </form>
</x-layouts.admin>
