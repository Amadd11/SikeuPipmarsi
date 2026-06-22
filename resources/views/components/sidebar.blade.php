{{-- Overlay Gelap untuk Mobile --}}
<div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="display: none;">
</div>

{{-- Sidebar Full Height dengan Hanya Sudut Kanan yang Melengkung (lg:rounded-r-3xl) --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-linear-to-b from-indigo-950 via-[#161436] to-[#0b091a] text-white flex flex-col h-full overflow-hidden shadow-2xl transition-all duration-300 ease-in-out 
           lg:static lg:translate-x-0 lg:flex lg:rounded-r-3xl lg:border-r lg:border-white/5 border-r border-white/5">

    {{-- Logo & Header --}}
    <div class="relative px-6 pt-8 pb-6 border-b border-white/5 bg-white/5 shrink-0">

        {{-- Tombol Close (Hanya di Mobile) --}}
        <button @click="sidebarOpen = false" aria-label="Tutup Menu"
            class="absolute top-4 right-4 text-indigo-300 hover:text-gold lg:hidden transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>

        <div class="flex items-center gap-3">
            {{-- Ikon Logo dengan Warna Kuning Emas (Gold) --}}
            <div
                class="w-10 h-10 bg-linear-to-br from-gold to-yellow-300 rounded-2xl flex items-center justify-center shadow-[0_0_15px_rgba(234,179,8,0.2)] shrink-0">
                <span class="material-symbols-outlined text-[#0b091a] text-3xl font-bold">account_balance</span>
            </div>
            <div>
                <span class="text-2xl font-black tracking-tighter text-white">SIKEU</span>
                <span class="text-2xl font-black tracking-tighter text-gold">PIPMARSI</span>
            </div>
        </div>

        <p class="text-xs text-indigo-200/50 mt-1 tracking-widest font-medium">
            SISTEM INFORMASI KEUANGAN
        </p>

        {{-- Badge Tahun Anggaran nuansa Emas --}}
        <div
            class="mt-4 inline-flex items-center gap-2 bg-gold/10 border border-gold/10 text-gold text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
            <span class="w-2 h-2 bg-gold rounded-full animate-pulse shadow-[0_0_8px_rgba(234,179,8,0.6)]"></span>
            TA {{ date('Y') }}
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto scrollbar-hide">

        {{-- UTAMA --}}
        <div>
            <p class="px-4 mb-3 text-[10px] font-bold tracking-[0.2em] uppercase text-indigo-300/40">
                Utama
            </p>
            <x-nav-link href="{{ route('dashboard') }}" icon="dashboard" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>
        </div>

        {{-- KEUANGAN --}}
        <div>
            <p class="px-4 mb-3 text-[10px] font-bold tracking-[0.2em] uppercase text-indigo-300/40">
                Keuangan
            </p>
            <x-nav-link href="{{ route('pendapatan.index') }}" icon="payments" :active="request()->routeIs('pendapatan.index')">
                Rencana Pendapatan
            </x-nav-link>
            <x-nav-link href="#" icon="receipt_long" :active="request()->routeIs('rencana-pengeluaran.*')">
                Rencana Pengeluaran
            </x-nav-link>
            <x-nav-link href="#" icon="sync_alt" :active="request()->routeIs('aktivitas-realisasi.*')">
                Aktivitas & Realisasi
            </x-nav-link>
        </div>

        {{-- MUTU & EVALUASI --}}
        <div>
            <p class="px-4 mb-3 text-[10px] font-bold tracking-[0.2em] uppercase text-indigo-300/40">
                Mutu & Evaluasi
            </p>
            <x-nav-link href="#" icon="verified" :active="request()->routeIs('indikator-mutu.*')">
                Indikator Mutu
            </x-nav-link>
            <x-nav-link href="#" icon="bar_chart" :active="request()->routeIs('rekapitulasi.*')">
                Rekapitulasi
            </x-nav-link>
            <x-nav-link href="#" icon="manage_search" :active="request()->routeIs('audit-monitoring.*')">
                Audit & Monitoring
            </x-nav-link>
        </div>

        {{-- OUTPUT --}}
        <div>
            <p class="px-4 mb-3 text-[10px] font-bold tracking-[0.2em] uppercase text-indigo-300/40">
                Output
            </p>
            <x-nav-link href="#" icon="print" :active="request()->routeIs('cetak-laporan.*')">
                Cetak Laporan
            </x-nav-link>
        </div>
    </nav>

    {{-- LOGOUT --}}
    <div class="p-4 border-t border-white/5 bg-white/2 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-indigo-200 hover:text-white hover:bg-white/10 rounded-2xl transition-all duration-200 group">
                <span
                    class="material-symbols-outlined text-red-400 group-hover:text-red-300 group-hover:-translate-x-1 transition-transform">
                    logout
                </span>
                Logout
            </button>
        </form>
    </div>

</aside>
