<section>
    <header class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-slate-100 text-slate-500 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-800">
                {{ __('Informasi Profil') }}
            </h2>
        </div>
        <p class="text-sm text-slate-500 font-medium">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-500 font-bold text-xs uppercase tracking-wider mb-2 block" />
            <x-text-input id="name" name="name" type="text"
                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-200 font-semibold text-slate-900"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-slate-500 font-bold text-xs uppercase tracking-wider mb-2 block" />
            <x-text-input id="email" name="email" type="email"
                class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3.5 px-4 transition-all duration-200 font-semibold text-slate-900"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
                    <p class="text-sm text-amber-800 flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>
                            {{ __('Alamat email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="underline text-sm text-amber-600 hover:text-amber-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-bold ml-1 transition-colors">
                                {{ __('Kirim ulang email verifikasi.') }}
                            </button>
                        </span>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-sm text-emerald-600 pl-7">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 font-bold transition-all duration-200 focus:ring-4 focus:ring-indigo-500/30 text-sm">
                {{ __('Simpan Perubahan') }}
            </button>

            {{-- Custom Toast Notification --}}
            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     x-init="setTimeout(() => show = false, 3000)"
                     class="flex items-center gap-2 text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Tersimpan.') }}
                </div>
            @endif
        </div>
    </form>
</section>
