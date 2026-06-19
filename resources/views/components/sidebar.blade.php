<div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="display: none;">
</div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0a2419] text-white flex flex-col h-full overflow-hidden border-r border-white/10 shadow-xl transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:flex">

    {{-- Logo & Header --}}
    <div class="relative px-6 pt-8 pb-6 border-b border-white/10">

        {{-- Tombol Close (Hanya di Mobile) --}}
        <button @click="sidebarOpen = false"
            class="absolute top-4 right-4 text-gray-400 hover:text-white lg:hidden transition">
            <span class="material-symbols-outlined">close</span>
        </button>

        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-linear-to-br from-yellow-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-[#0a2419] text-3xl font-bold">account_balance</span>
            </div>
            <div>
                <span class="text-2xl font-black tracking-tighter text-white">SIKEU</span>
                <span class="text-2xl font-black tracking-tighter text-yellow-400">PIPMARSI</span>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-1 tracking-widest font-medium">
            SISTEM INFORMASI KEUANGAN
        </p>
        <div
            class="mt-4 inline-flex items-center gap-2 bg-white/10 text-white/90 text-xs font-medium px-3 py-1 rounded-full">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            TA {{ date('Y') }}
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto scrollbar-hide">

        {{-- UTAMA --}}
        <div>
            <p class="px-4 mb-3 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Utama
            </p>
            <x-nav-link href="{{ route('dashboard') }}" icon="dashboard" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>
        </div>

        {{-- KEUANGAN --}}
        <div>
            <p class="px-4 mb-3 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Keuangan
            </p>
            <x-nav-link href="#" icon="payments" :active="request()->routeIs('rencana-pendapatan.*')">
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
            <p class="px-4 mb-3 text-xs font-semibold tracking-widest uppercase text-gray-400">
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
            <p class="px-4 mb-3 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Output
            </p>
            <x-nav-link href="#" icon="print" :active="request()->routeIs('cetak-laporan.*')">
                Cetak Laporan
            </x-nav-link>
        </div>

        {{-- LOGOUT --}}
        <div class="pt-6 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-300 hover:text-red-200 hover:bg-red-500/10 rounded-2xl transition group">
                    <span class="material-symbols-outlined group-hover:text-red-400 transition">
                        logout
                    </span>
                    Logout
                </button>
            </form>
        </div>
    </nav>

</aside>
