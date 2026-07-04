{{-- Toast/flash pesan (PRD §7.6 — copy aktif). --}}
@if (session('status') || session('success') || session('error'))
    @php
        $message = session('success') ?? session('status') ?? session('error');
        $isError = (bool) session('error');
    @endphp
    <div
        data-flash
        role="status"
        class="fixed bottom-6 right-6 z-50 max-w-sm rounded-lg border px-4 py-3 text-sm shadow-lg {{ $isError ? 'border-signal/40 bg-signal/10 text-signal' : 'border-brand/30 bg-white text-ink' }}"
    >
        {{ $message }}
    </div>
    <script>
        setTimeout(() => document.querySelector('[data-flash]')?.remove(), 4000);
    </script>
@endif
