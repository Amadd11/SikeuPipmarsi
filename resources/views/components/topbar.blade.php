@props(['title' => 'Dashboard'])

{{-- Menggunakan div karena di app.blade.php sudah dibungkus tag <header> --}}
<div class="h-16 px-4 sm:px-6 flex items-center justify-between shrink-0">

    <div class="flex items-center gap-2 sm:gap-3">
        {{-- TOMBOL HAMBURGER --}}
        <button @click="sidebarOpen = true"
            class="p-2 -ml-2 text-gray-600 rounded-lg lg:hidden hover:bg-gray-100 hover:text-[#0a2419] transition-colors"
            aria-label="Buka Menu">
            <span class="material-symbols-outlined text-2xl mt-1">menu</span>
        </button>

        {{-- Judul Halaman --}}
        <h1 class="text-xl sm:text-2xl font-bold text-[#0a2419] truncate">{{ $title }}</h1>
    </div>

    {{-- Info Kanan --}}
    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        {{-- Jam Live --}}
        <div x-data="{ time: '' }" 
             x-init="
                const updateTime = () => { time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'}) };
                updateTime();
                setInterval(updateTime, 1000);
             "
             class="hidden md:flex items-center gap-1.5 text-gray-500 bg-white shadow-sm border border-gray-100 px-3 py-1.5 rounded-full">
            <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
            <span class="text-sm font-semibold tracking-wider mt-0.5" x-text="time"></span>
        </div>

        {{-- Sembunyikan label TA di HP agar tidak terlalu sempit --}}
        <span
            class="hidden sm:inline-block px-3 sm:px-4 py-1.5 bg-sidebar text-white text-xs sm:text-sm font-semibold rounded-full">
            TA {{ date('Y') }}
        </span>

        <div
            class="w-8 h-8 sm:w-9 sm:h-9 bg-gold rounded-full flex items-center justify-center text-gray-900 text-sm font-semibold cursor-pointer">
            {{ strtoupper(substr(auth()->user()->name ?? 'US', 0, 2)) }}
        </div>
    </div>

</div>
