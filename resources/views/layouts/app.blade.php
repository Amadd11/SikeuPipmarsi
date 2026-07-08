<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>{{ config('app.name', 'SIKEU PIPMARSI') }} — {{ $title ?? 'Dashboard' }}</title>

    {{-- Poppins Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    {{-- Material Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #0a2419;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f7f9] antialiased text-gray-800">

    {{-- BUNGKUS ALPINE.JS UTAMA --}}
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden relative">

        {{-- Overlay Gelap untuk Mobile --}}
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="display: none;">
        </div>

        <x-sidebar />
        
        {{-- Main Area --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar (glass effect modern SaaS) --}}
            <header class="bg-white/70 backdrop-blur-md border-b border-gray-200 sticky top-0 z-30">
                <x-topbar :title="$title ?? 'Dashboard'" />
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-350 mx-auto">
                    {{ $slot }}
                </div>
            </main>

        </div>

    </div>

    {{-- Global Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-4 right-4 z-50 flex items-center gap-3 bg-white border border-green-100 text-gray-800 shadow-xl rounded-2xl px-5 py-4 max-w-sm">
            
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
            </div>
            
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-gray-900">Berhasil!</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ session('success') }}</p>
            </div>
            
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-4 right-4 z-50 flex items-center gap-3 bg-white border border-red-100 text-gray-800 shadow-xl rounded-2xl px-5 py-4 max-w-sm">
            
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-red-500">error</span>
            </div>
            
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-gray-900">Gagal!</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ session('error') }}</p>
            </div>
            
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    @endif

</body>

</html>
