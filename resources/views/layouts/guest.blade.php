<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'SIPANDA PIPMARSI')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-pipmarsi.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-zinc-100 font-sans min-h-screen flex items-center justify-center overflow-hidden">

    <div class="w-full h-screen grid lg:grid-cols-2 bg-white shadow-2xl overflow-hidden">
        {{-- Left: form --}}
        <div class="flex flex-col justify-center p-8 lg:p-12 xl:p-20 bg-white">
            <div class="max-w-md mx-auto w-full">
                {{ $slot }}
            </div>
        </div>

        {{-- Right: brand panel --}}
        <x-brand-panel />
    </div>

    @stack('scripts')
</body>

</html>
