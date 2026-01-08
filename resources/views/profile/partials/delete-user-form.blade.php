<section class="space-y-6">
    <header class="flex items-start gap-4">
        <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-rose-600">
                {{ __('Hapus Akun') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                {{ __('Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. Harap unduh data penting sebelum melanjutkan.') }}
            </p>
        </div>
    </header>

    <div class="flex justify-end">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-rose-600 hover:bg-rose-700 rounded-xl px-6 py-2.5"
        >{{ __('Hapus Akun Saya') }}</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ __('Tindakan ini tidak dapat dibatalkan. Silakan masukkan password Anda untuk konfirmasi penghapusan akun permanen.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-3/4 rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500"
                    placeholder="{{ __('Password Anda') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl px-4 py-2">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="bg-rose-600 hover:bg-rose-700 rounded-xl px-4 py-2 ms-3">
                    {{ __('Ya, Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
