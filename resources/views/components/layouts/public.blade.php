@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? 'NewsPortal — kabar terbaru seputar teknologi, olahraga, politik, pendidikan, dan ekonomi.' }}">
    <title>{{ $title ? $title.' — NewsPortal' : 'NewsPortal — Kabar tepercaya setiap hari' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper text-ink antialiased">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-ink focus:px-4 focus:py-2 focus:text-white">Lompat ke konten</a>

    {{-- MASTHEAD --}}
    <header class="border-b border-line bg-paper">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-baseline gap-2">
                <span class="font-[family-name:var(--font-display)] text-2xl font-extrabold tracking-tight sm:text-3xl">NewsPortal</span>
                <span class="hidden h-2 w-2 rounded-full bg-signal sm:inline-block" title="Terkini"></span>
            </a>

            <div class="hidden text-right sm:block">
                <p class="kicker text-muted">{{ \Illuminate\Support\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('search') }}" method="GET" class="hidden items-center md:flex" role="search">
                    <label for="q-top" class="sr-only">Cari berita</label>
                    <input
                        id="q-top"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari berita…"
                        class="w-44 rounded-l-md border border-line bg-white px-3 py-1.5 text-sm focus:border-action focus:outline-none"
                    >
                    <button type="submit" class="rounded-r-md border border-l-0 border-line bg-ink px-3 py-1.5 text-sm text-white hover:bg-brand">Cari</button>
                </form>

                <button
                    type="button"
                    data-nav-toggle
                    aria-expanded="false"
                    aria-label="Buka menu"
                    class="rounded-md border border-line p-2 md:hidden"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- NAVBAR --}}
        <nav class="border-t border-line" aria-label="Navigasi utama">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <ul data-nav-menu class="hidden flex-col gap-1 py-2 text-sm font-medium md:flex md:flex-row md:items-center md:gap-6 md:py-0">
                    <li>
                        <a href="{{ route('home') }}" class="block py-2 transition-colors hover:text-brand {{ request()->routeIs('home') ? 'text-brand' : '' }}">Beranda</a>
                    </li>
                    @foreach ($navCategories as $navCategory)
                        <li>
                            <a href="{{ route('categories.show', $navCategory) }}" class="flex items-center gap-1.5 py-2 transition-colors hover:text-brand {{ request()->routeIs('categories.show') && request()->route('category')?->id === $navCategory->id ? 'text-brand' : '' }}">
                                <span class="inline-block h-1.5 w-1.5 rounded-full" style="background-color: {{ $navCategory->color }}"></span>
                                {{ $navCategory->name }}
                            </a>
                        </li>
                    @endforeach
                    <li><a href="{{ route('about') }}" class="block py-2 transition-colors hover:text-brand {{ request()->routeIs('about') ? 'text-brand' : '' }}">Tentang</a></li>
                    <li class="md:ml-auto">
                        @auth
                            <a href="{{ route('dashboard') }}" class="block py-2 font-semibold text-brand">Dasbor</a>
                        @else
                            <a href="{{ route('login') }}" class="block py-2 font-semibold text-action">Login</a>
                        @endauth
                    </li>

                    {{-- Pencarian versi mobile --}}
                    <li class="md:hidden">
                        <form action="{{ route('search') }}" method="GET" class="flex items-center py-2" role="search">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita…" class="w-full rounded-l-md border border-line bg-white px-3 py-1.5 text-sm focus:border-action focus:outline-none">
                            <button type="submit" class="rounded-r-md border border-l-0 border-line bg-ink px-3 py-1.5 text-sm text-white">Cari</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main id="konten" class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-line bg-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-8 text-sm text-muted sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="font-[family-name:var(--font-display)] font-bold text-ink">NewsPortal</p>
            <p>&copy; {{ date('Y') }} NewsPortal. Kabar tepercaya setiap hari.</p>
            <a href="{{ route('about') }}" class="hover:text-brand">Tentang redaksi</a>
        </div>
    </footer>

    <x-flash />
</body>
</html>
