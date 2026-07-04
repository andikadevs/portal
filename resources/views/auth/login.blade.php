<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — NewsPortal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-paper px-4 py-10 text-ink antialiased">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="font-[family-name:var(--font-display)] text-3xl font-extrabold tracking-tight">NewsPortal</a>
            <p class="kicker mt-2 text-muted">Masuk ke ruang redaksi</p>
        </div>

        <div class="rounded-xl border border-line bg-white p-6 shadow-sm sm:p-8">
            @if (session('status'))
                <p class="mb-4 rounded-md bg-brand/10 px-3 py-2 text-sm text-brand">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md border border-signal/40 bg-signal/10 px-3 py-2 text-sm text-signal">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('email') border-signal @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-signal">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium">Kata sandi</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-md border border-line bg-paper px-3 py-2 text-sm focus:border-action focus:outline-none @error('password') border-signal @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-signal">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remember" class="rounded border-line text-action focus:ring-action">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-md bg-action px-4 py-2.5 font-semibold text-white transition-colors hover:bg-brand">
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-muted">
            <a href="{{ route('home') }}" class="hover:text-brand">&larr; Kembali ke beranda</a>
        </p>
    </div>
</body>
</html>
