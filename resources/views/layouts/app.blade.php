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

</body>

</html>
