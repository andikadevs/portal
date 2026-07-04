<x-layouts.admin title="Sunting Artikel" heading="Sunting artikel" :subheading="$article->title">
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.articles._form', ['submitLabel' => 'Perbarui artikel'])
    </form>
</x-layouts.admin>
