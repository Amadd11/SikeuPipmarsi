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
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        {{-- Sembunyikan label TA di HP agar tidak terlalu sempit --}}
        <span
            class="hidden sm:inline-block px-3 sm:px-4 py-1.5 bg-sidebar text-white text-xs sm:text-sm font-semibold rounded-full">
            TA {{ date('Y') }}
        </span>

        <div
            class="w-8 h-8 sm:w-9 sm:h-9 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold cursor-pointer">
            {{ strtoupper(substr(auth()->user()->name ?? 'US', 0, 2)) }}
        </div>
    </div>

</div>
