<div
    class="hidden lg:flex relative items-center justify-center overflow-hidden bg-linear-to-br from-sidebar via-sidebar to-primary-dark">

    {{-- Latar Belakang Cahaya (Ambient Glowing Orbs) --}}
    <div class="absolute -top-40 -right-40 w-150 h-150 bg-gold/10 rounded-full blur-[120px] pointer-events-none">
    </div>
    <div
        class="absolute -bottom-40 -left-40 w-125 h-125 bg-primary/20 rounded-full blur-[100px] pointer-events-none">
    </div>

    {{-- Grid Pattern Halus untuk Tekstur --}}
    <div
        class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz48L3N2Zz4=')]">
    </div>

    {{-- Konten Utama --}}
    <div class="relative z-10 text-center px-12 w-full max-w-2xl">

        {{-- Ikon Utama (Wadah Lingkaran Minimalis dengan Aksen Gold) --}}
        <div
            class="w-40 h-40 mx-auto mb-10 bg-white/5 backdrop-blur-xl rounded-full flex items-center justify-center border border-white/10 shadow-[0_0_50px_rgba(234,179,8,0.15)] relative">
            {{-- Lingkaran aksen tipis di luar --}}
            <div class="absolute inset-0 rounded-full border border-gold/20 scale-110"></div>

            <span class="material-symbols-outlined text-[80px] text-gold drop-shadow-lg font-light">
                account_balance_wallet
            </span>
        </div>

        {{-- Tipografi Utama --}}
        <h2 class="text-4xl lg:text-5xl font-light tracking-tight text-white leading-tight mb-5">
            Finansial <span
                class="font-semibold text-transparent bg-clip-text bg-linear-to-r from-gold to-yellow-200">Terintegrasi</span>
        </h2>

        {{-- Garis Pembatas Tipis --}}
        <div class="w-16 h-0.5 bg-gold/30 mx-auto mb-6 rounded-full"></div>

        <p class="max-w-md mx-auto text-purple-200/80 text-lg font-light leading-relaxed">
            Manajemen keuangan modern untuk mendukung ekosistem organisasi PIPMARSI.
        </p>
    </div>

    {{-- Floating Elements Modern (Lingkaran Kaca Melayang) --}}
    <div
        class="absolute bottom-1/4 right-16 w-32 h-32 bg-linear-to-tr from-white/5 to-white/10 rounded-full backdrop-blur-md border border-white/10 shadow-2xl animate-[pulse_6s_ease-in-out_infinite]">
    </div>
    <div
        class="absolute top-32 left-16 w-20 h-20 bg-linear-to-br from-gold/10 to-transparent rounded-full backdrop-blur-md border border-gold/20 shadow-xl animate-[pulse_4s_ease-in-out_infinite_reverse]">
    </div>

    {{-- Footer Label (Sistem Informasi Keuangan) --}}
    <div class="absolute bottom-12 left-12 text-purple-300/60">
        <div class="flex items-center gap-4">
            <div class="h-px w-16 bg-linear-to-r from-transparent to-purple-300/50"></div>
            <span class="uppercase text-[10px] tracking-[4px] font-bold">
                Sistem Informasi Keuangan
            </span>
            <div class="h-px w-16 bg-linear-to-l from-transparent to-purple-300/50"></div>
        </div>
    </div>
</div>
