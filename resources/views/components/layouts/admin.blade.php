@props(['title' => null, 'heading' => 'Dasbor', 'subheading' => null])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>document.documentElement.classList.add('js')</script>
    <title>{{ $title ? $title.' — Dasbor NewsPortal' : 'Dasbor NewsPortal' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface text-ink antialiased">
    {{-- SIDEBAR — tetap di kiri, tidak ikut ter-scroll (fixed) --}}
    <aside class="z-40 flex flex-col border-b border-white/10 bg-ink text-white md:fixed md:inset-y-0 md:left-0 md:w-64 md:border-b-0 md:border-r md:border-white/10 md:overflow-y-auto no-scrollbar">
        <div class="flex items-center justify-between px-5 py-4 md:border-b md:border-white/10">
            <a href="{{ route('dashboard') }}" class="section-title text-xl">NewsPortal</a>
            <button type="button" data-nav-toggle aria-expanded="false" aria-label="Buka menu" class="rounded p-1.5 hover:bg-white/10 md:hidden">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <nav data-nav-menu class="hidden flex-1 flex-col gap-0.5 px-3 pb-4 pt-2 text-sm md:flex" aria-label="Navigasi dasbor">
            @php
                $item = function (string $route, string $label, bool $active) {
                    $base = 'flex items-center gap-2 rounded-md border-l-2 px-3 py-2 transition-colors ';
                    $state = $active
                        ? 'border-signal bg-white/10 font-semibold text-white'
                        : 'border-transparent text-white/70 hover:bg-white/5 hover:text-white';
                    return '<a href="'.$route.'" class="'.$base.$state.'">'.$label.'</a>';
                };
            @endphp

            <p class="px-3 pb-1 pt-2 kicker text-white/35">Umum</p>
            {!! $item(route('dashboard'), 'Dasbor', request()->routeIs('dashboard')) !!}

            <p class="px-3 pb-1 pt-4 kicker text-white/35">Konten</p>
            {!! $item(route('admin.articles.index'), 'Artikel', request()->routeIs('admin.articles.*')) !!}
            {!! $item(route('admin.categories.index'), 'Kategori', request()->routeIs('admin.categories.*')) !!}

            @if (auth()->user()->isKetua())
                <p class="px-3 pb-1 pt-4 kicker text-white/35">Ketua</p>
                {!! $item(route('admin.users.index'), 'Manajemen User', request()->routeIs('admin.users.*')) !!}
                {!! $item(route('admin.statistik'), 'Statistik', request()->routeIs('admin.statistik')) !!}
            @endif

            <div class="mt-auto space-y-0.5 border-t border-white/10 pt-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-white/70 transition-colors hover:bg-white/5 hover:text-white">Lihat situs ↗</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-white/70 transition-colors hover:bg-white/5 hover:text-white">Keluar</button>
                </form>
                <p class="px-3 pt-2 text-[0.7rem] text-white/35">
                    Dev by
                    <a href="https://andikads.vercel.app" target="_blank" rel="noopener" class="font-semibold text-white/60 hover:text-white hover:underline">Andika</a>
                </p>
            </div>
        </nav>
    </aside>

    {{-- KONTEN — diberi margin kiri agar tidak tertutup sidebar tetap --}}
    <div class="flex min-h-screen flex-col md:pl-64">
        <header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-line bg-paper/95 px-5 py-3 backdrop-blur sm:px-6">
            <div class="min-w-0">
                <h1 class="section-title truncate text-lg">{{ $heading ?? 'Dasbor' }}</h1>
                @isset($subheading)
                    <p class="truncate text-sm text-muted">{{ $subheading }}</p>
                @endisset
            </div>
            <div class="flex items-center gap-3 text-right">
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold leading-tight">{{ auth()->user()->name }}</p>
                    <p class="kicker text-muted">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand text-sm font-bold text-white">
                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
        </header>

        <main class="flex-1 p-5 sm:p-8">
            <div class="mx-auto max-w-6xl">
                {{ $slot }}
            </div>
        </main>
    </div>

    <x-flash />
    @stack('scripts')
</body>
</html>
