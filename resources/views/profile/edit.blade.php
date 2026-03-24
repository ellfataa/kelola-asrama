<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Pengaturan Profil') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-2 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Form Info Profil --}}
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-slate-200 rounded-3xl h-fit hover:shadow-lg transition-all duration-300">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Form Update Password --}}
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-slate-200 rounded-3xl h-fit hover:shadow-lg transition-all duration-300">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Form Hapus Akun --}}
            <div class="p-6 sm:p-8 bg-rose-50/30 shadow-sm border border-rose-200 rounded-3xl hover:shadow-lg transition-all duration-300">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

    {{-- Script notifikasi Hapus Akun --}}
    @if (session('status') === 'account-deleted')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Akun Anda Berhasil Dihapus.',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.href = "{{ url('/') }}";
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
