<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Selamat Datang</h2>
        <p class="text-sm text-slate-500">Silakan masuk untuk melanjutkan.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-slate-700 font-semibold text-xs uppercase tracking-wide mb-1" />
                <x-text-input id="email"
                              class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4 transition-all duration-200 text-sm"
                              type="email"
                              name="email"
                              :value="old('email')"
                              required autofocus
                              autocomplete="username"
                              placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold text-xs uppercase tracking-wide mb-1" />
                <x-text-input id="password"
                              class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4 transition-all duration-200 text-sm"
                              type="password"
                              name="password"
                              required
                              autocomplete="current-password"
                              placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-2">
            <a href="/" class="inline-flex justify-center items-center px-4 py-3 bg-white border border-slate-300 rounded-xl font-bold text-sm text-slate-700 uppercase tracking-widest hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ __('Kembali') }}
            </a>

            <button type="submit" class="inline-flex justify-center items-center px-4 py-3 bg-slate-900 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-slate-800 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                {{ __('Masuk') }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>

        <div class="relative mt-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-white px-2 text-slate-400">Atau</span>
            </div>
        </div>

        <div class="text-center mt-4 pb-2">
            <p class="text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
