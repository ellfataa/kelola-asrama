<section class="space-y-6 relative overflow-hidden">
    {{-- Elemen Desain Dekoratif --}}
    <div class="absolute right-0 top-0 w-48 h-48 bg-rose-100/40 rounded-bl-full -mr-10 -mt-10 pointer-events-none"></div>

    <header class="flex flex-col sm:flex-row items-start gap-4 relative z-10">
        <div class="p-3 bg-rose-100 rounded-xl text-rose-600 shadow-sm border border-rose-200 shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <h2 class="text-lg font-extrabold text-rose-600 uppercase tracking-wide">
                {{ __('Hapus Akun') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 font-medium leading-relaxed max-w-2xl">
                {{ __('Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan. Harap unduh atau salin data penting Anda sebelum melanjutkan.') }}
            </p>
        </div>
    </header>

    <div class="flex sm:justify-end relative z-10 pt-4 border-t border-rose-100/50">
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-full sm:w-auto px-6 py-3.5 bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-lg hover:shadow-rose-500/30 transform hover:-translate-y-0.5 font-bold transition-all duration-200 focus:ring-4 focus:ring-rose-500/30 text-sm flex items-center justify-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            {{ __('Hapus Akun Saya Permanen') }}
        </button>
    </div>

    {{-- MODAL KONFIRMASI --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-rose-100 text-rose-600 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-xl font-extrabold text-slate-900">
                    {{ __('Konfirmasi Penghapusan') }}
                </h2>
            </div>

            <p class="mt-2 text-sm text-slate-600 font-medium bg-rose-50 p-4 rounded-xl border border-rose-100">
                {{ __('Apakah Anda benar-benar yakin? Tindakan ini tidak dapat dibatalkan. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password Anda') }}" class="text-slate-500 font-bold text-xs uppercase tracking-wider mb-2 block" />

                <div x-data="{ showPassword: false }" class="relative">
                    <input
                        id="password"
                        name="password"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 py-3.5 px-4 pr-12 transition-all duration-200 font-semibold text-slate-900"
                        placeholder="{{ __('Masukkan password untuk konfirmasi...') }}"
                        x-bind:type="showPassword ? 'text' : 'password'"
                    />
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" aria-label="Toggle password visibility">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.107.424.107.639a1.012 1.012 0 01-.1.639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178a1.012 1.012 0 01-.001-.639z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path></svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-500 font-bold text-sm" />
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="w-full sm:w-auto px-6 py-3.5 bg-white text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 font-bold transition-colors text-sm text-center">
                    {{ __('Batal') }}
                </button>

                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-rose-600 text-white rounded-xl hover:bg-rose-700 shadow-lg hover:shadow-rose-500/30 transform hover:-translate-y-0.5 font-bold transition-all duration-200 focus:ring-4 focus:ring-rose-500/30 text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    {{ __('Ya, Hapus Permanen') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
