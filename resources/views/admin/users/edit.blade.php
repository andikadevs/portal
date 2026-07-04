<x-layouts.admin title="Sunting Pengguna" heading="Sunting pengguna" :subheading="$user->name">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @method('PUT')
        @include('admin.users._form', ['submitLabel' => 'Perbarui pengguna'])
    </form>
</x-layouts.admin>
