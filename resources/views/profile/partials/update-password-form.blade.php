<section>
    <header>
        <h2 class="text-lg font-bold text-slate-800">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-slate-700 font-semibold text-xs uppercase tracking-wide mb-1" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4 transition-all duration-200 text-sm"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-slate-700 font-semibold text-xs uppercase tracking-wide mb-1" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4 transition-all duration-200 text-sm"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-700 font-semibold text-xs uppercase tracking-wide mb-1" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4 transition-all duration-200 text-sm"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-slate-900 hover:bg-slate-800 rounded-xl px-6 py-2.5">{{ __('Update Password') }}</x-primary-button>

            {{-- Custom Toast Notification --}}
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 z-50">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold text-sm">{{ __('Password Berhasil Diubah!') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
