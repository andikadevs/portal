@props(['title' => null, 'heading' => 'Dasbor', 'subheading' => null])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' — Dasbor NewsPortal' : 'Dasbor NewsPortal' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper text-ink antialiased">
    <div class="flex min-h-screen flex-col md:flex-row">
        {{-- SIDEBAR role-aware (PRD §7.4 / F-15) --}}
        <aside class="flex flex-col border-b border-line bg-ink text-white md:min-h-screen md:w-64 md:border-b-0 md:border-r">
            <div class="flex items-center justify-between px-5 py-4">
                <a href="{{ route('dashboard') }}" class="font-[family-name:var(--font-display)] text-xl font-extrabold tracking-tight">NewsPortal</a>
                <button type="button" data-nav-toggle aria-expanded="false" aria-label="Buka menu" class="rounded p-1.5 hover:bg-white/10 md:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <nav data-nav-menu class="hidden flex-1 flex-col gap-1 px-3 pb-4 text-sm md:flex" aria-label="Navigasi dasbor">
                @php
                    $navItem = fn (string $route, string $label, bool $active) =>
                        '<a href="'.$route.'" class="flex items-center gap-2 rounded-md px-3 py-2 transition-colors '.($active ? 'bg-white/15 font-semibold text-white' : 'text-white/75 hover:bg-white/10 hover:text-white').'">'.$label.'</a>';
                @endphp

                <p class="px-3 pb-1 pt-2 text-[0.68rem] font-semibold uppercase tracking-wider text-white/40">Umum</p>
                {!! $navItem(route('dashboard'), 'Dasbor', request()->routeIs('dashboard')) !!}

                <p class="px-3 pb-1 pt-3 text-[0.68rem] font-semibold uppercase tracking-wider text-white/40">Konten</p>
                {!! $navItem(route('admin.articles.index'), 'Artikel', request()->routeIs('admin.articles.*')) !!}
                {!! $navItem(route('admin.categories.index'), 'Kategori', request()->routeIs('admin.categories.*')) !!}

                @if (auth()->user()->isKetua())
                    <p class="px-3 pb-1 pt-3 text-[0.68rem] font-semibold uppercase tracking-wider text-white/40">Ketua</p>
                    {!! $navItem(route('admin.users.index'), 'Manajemen User', request()->routeIs('admin.users.*')) !!}
                    {!! $navItem(route('admin.statistik'), 'Statistik', request()->routeIs('admin.statistik')) !!}
                @endif

                <div class="mt-auto space-y-1 border-t border-white/10 pt-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-white/75 transition-colors hover:bg-white/10 hover:text-white">Lihat situs</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-white/75 transition-colors hover:bg-white/10 hover:text-white">Keluar</button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- KONTEN --}}
        <div class="flex flex-1 flex-col">
            <header class="flex items-center justify-between gap-4 border-b border-line bg-white px-5 py-3">
                <div>
                    <h1 class="font-[family-name:var(--font-display)] text-lg font-bold">{{ $heading ?? 'Dasbor' }}</h1>
                    @isset($subheading)
                        <p class="text-sm text-muted">{{ $subheading }}</p>
                    @endisset
                </div>
                <div class="flex items-center gap-3 text-right">
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="kicker text-muted">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand text-sm font-bold text-white">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
            </header>

            <main class="flex-1 p-5 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-flash />
</body>
</html>
