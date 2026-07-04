@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>document.documentElement.classList.add('js')</script>
    <meta name="description" content="{{ $description ?? 'NewsPortal — kabar terbaru seputar teknologi, olahraga, politik, pendidikan, dan ekonomi.' }}">
    <title>{{ $title ? $title.' — NewsPortal' : 'NewsPortal — Kabar tepercaya setiap hari' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface text-ink antialiased">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-ink focus:px-4 focus:py-2 focus:text-white">Lompat ke konten</a>

    <header>
        {{-- UTILITY BAR --}}
        <div class="border-b border-line bg-ink text-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-1.5 sm:px-6">
                <p class="meta text-white/70">{{ \Illuminate\Support\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <div class="flex items-center gap-4">
                    <span class="hidden meta text-white/60 sm:inline">Kabar tepercaya setiap hari</span>
                    @auth
                        <a href="{{ route('dashboard') }}" class="kicker text-white/90 hover:text-white">Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="kicker text-white/90 hover:text-white">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- MASTHEAD --}}
        <div class="border-b-2 border-line-strong bg-paper">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-5 sm:px-6">
                <button type="button" data-nav-toggle aria-expanded="false" aria-label="Buka menu" class="rounded-md border border-line p-2 md:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <a href="{{ route('home') }}" class="mx-auto flex items-baseline gap-1.5 md:mx-0" aria-label="NewsPortal beranda">
                    <span class="section-title text-3xl leading-none sm:text-4xl">NewsPortal</span>
                    <span class="hidden h-2.5 w-2.5 rounded-full bg-signal sm:inline-block" title="Terkini"></span>
                </a>

                <form action="{{ route('search') }}" method="GET" class="hidden items-center md:flex" role="search">
                    <label for="q-top" class="sr-only">Cari berita</label>
                    <input id="q-top" type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita…"
                        class="w-48 rounded-l-md border border-line bg-surface px-3 py-2 text-sm focus:border-action focus:bg-paper focus:outline-none">
                    <button type="submit" class="rounded-r-md border border-l-0 border-ink bg-ink px-3 py-2 text-sm font-medium text-white hover:bg-brand">Cari</button>
                </form>

                <span class="w-9 md:hidden" aria-hidden="true"></span>
            </div>
        </div>

        {{-- NAVBAR (sticky) --}}
        <nav class="sticky top-0 z-40 border-b border-line bg-paper/95 backdrop-blur" aria-label="Navigasi utama">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <ul data-nav-menu class="hidden flex-col gap-0 py-1 text-sm font-semibold md:flex md:flex-row md:items-center md:gap-1 md:py-0">
                    <li>
                        <a href="{{ route('home') }}" class="block border-b-2 py-2.5 md:px-3 transition-colors hover:text-brand {{ request()->routeIs('home') ? 'border-signal text-brand' : 'border-transparent' }}">Beranda</a>
                    </li>
                    @foreach ($navCategories as $navCategory)
                        @php $activeCat = request()->routeIs('categories.show') && request()->route('category')?->id === $navCategory->id; @endphp
                        <li>
                            <a href="{{ route('categories.show', $navCategory) }}" class="flex items-center gap-1.5 border-b-2 py-2.5 md:px-3 transition-colors hover:text-brand {{ $activeCat ? 'text-brand' : 'border-transparent' }}" @style(['border-color:'.$navCategory->color => $activeCat])>
                                <span class="inline-block h-1.5 w-1.5 rounded-full" style="background-color: {{ $navCategory->color }}"></span>
                                {{ $navCategory->name }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('about') }}" class="block border-b-2 py-2.5 md:px-3 transition-colors hover:text-brand {{ request()->routeIs('about') ? 'border-signal text-brand' : 'border-transparent' }}">Tentang</a>
                    </li>

                    <li class="md:hidden">
                        <form action="{{ route('search') }}" method="GET" class="flex items-center py-2" role="search">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita…" class="w-full rounded-l-md border border-line bg-surface px-3 py-1.5 text-sm focus:border-action focus:outline-none">
                            <button type="submit" class="rounded-r-md border border-l-0 border-ink bg-ink px-3 py-1.5 text-sm text-white">Cari</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main id="konten" class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        {{ $slot }}
    </main>

    <footer class="mt-20 border-t-2 border-line-strong bg-ink text-white/80">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <p class="section-title text-2xl text-white">NewsPortal</p>
                    <p class="mt-3 max-w-xs text-sm text-white/60">Kabar tepercaya setiap hari — teknologi, olahraga, politik, pendidikan, dan ekonomi.</p>
                </div>
                <div>
                    <p class="kicker mb-3 text-white/50">Rubrik</p>
                    <ul class="space-y-2 text-sm">
                        @foreach ($navCategories as $navCategory)
                            <li><a href="{{ route('categories.show', $navCategory) }}" class="text-white/75 hover:text-white">{{ $navCategory->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="kicker mb-3 text-white/50">Portal</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-white/75 hover:text-white">Beranda</a></li>
                        <li><a href="{{ route('about') }}" class="text-white/75 hover:text-white">Tentang redaksi</a></li>
                        <li><a href="{{ route('search') }}" class="text-white/75 hover:text-white">Pencarian</a></li>
                        <li><a href="{{ route('login') }}" class="text-white/75 hover:text-white">Login redaksi</a></li>
                    </ul>
                </div>
                <div>
                    <p class="kicker mb-3 text-white/50">Redaksi</p>
                    <p class="text-sm text-white/60">Verifikasi berlapis sebelum terbit. Independen, akurat, jernih.</p>
                    <a href="mailto:redaksi@newsportal.test" class="mt-2 inline-block text-sm text-white/75 hover:text-white">redaksi@newsportal.test</a>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-6 text-sm text-white/50 sm:flex-row">
                <p>&copy; {{ date('Y') }} NewsPortal. Seluruh hak cipta dilindungi.</p>
                <p>Dikembangkan oleh
                    <a href="https://andikads.vercel.app" target="_blank" rel="noopener" class="font-semibold text-white/80 hover:text-white hover:underline">Andika</a>
                </p>
            </div>
        </div>
    </footer>

    <x-flash />
</body>
</html>
