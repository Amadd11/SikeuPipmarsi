<x-guest-layout>
    @section('title', 'Masuk')

    <div style="font-family: 'Poppins', sans-serif;">

        <div class="flex items-center gap-4 mb-10">
            <x-application-logo />
        </div>

        <h1 class="text-4xl font-semibold text-gray-900 mb-2">Masuk</h1>
        <p class="text-gray-600 mb-10">Selamat datang kembali di sistem keuangan PIPMARSI</p>

        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-2 block w-full"
                    placeholder="nama@pipmarsi.or.id" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <div>
                <div class="flex justify-between items-center">
                    <x-input-label for="password" :value="__('Kata Sandi')" />
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-primary font-medium hover:underline">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>
                <x-text-input id="password" name="password" type="password" class="mt-2 block w-full"
                    placeholder="••••••••" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-3">
                <input id="remember_me" name="remember" type="checkbox"
                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/20 cursor-pointer" />
                <label for="remember_me" class="text-sm text-gray-600 cursor-pointer select-none">
                    Ingat saya
                </label>
            </div>

            {{-- Submit --}}
            <x-primary-button class="w-full justify-center py-4 text-lg rounded-2xl">
                Masuk
            </x-primary-button>
        </form>

        <p class="text-center mt-8 text-gray-600 text-sm">
            Butuh akses?
            <a href="#" class="text-primary font-semibold hover:underline">Hubungi Administrator</a>
        </p>

    </div>
</x-guest-layout>
