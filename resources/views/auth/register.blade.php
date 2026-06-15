<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registrasi | Nautica</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="text-slate-900 antialiased bg-white min-h-screen flex overflow-hidden">

    {{-- KOLOM KIRI: Branding & Logo --}}
    <div class="hidden lg:flex lg:w-5/12 bg-slate-50 relative flex-col items-center justify-center p-8 text-center border-r border-slate-100">
        <div class="absolute inset-0" style="background-image: radial-gradient(rgba(15, 23, 42, 0.04) 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-100/70 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-100/70 rounded-full mix-blend-multiply filter blur-3xl"></div>

        <div class="relative z-10 flex flex-col items-center">
            <p class="text-indigo-600 text-[18px] font-extrabold tracking-widest uppercase mb-2">
                Kelola Asrama Kampus
            </p>

            <h2 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500 tracking-[0.2em] uppercase mb-8">
                Nautica
            </h2>

            <div class="w-32 h-32 bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 mb-8 transform transition duration-500 hover:-translate-y-1.5">
                <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-full h-full object-contain">
            </div>

            <h1 class="text-3xl font-extrabold text-slate-900 leading-tight">
                Akademi Maritim Nusantara<br>Cilacap
            </h1>
        </div>
    </div>

    {{-- KOLOM KANAN: Form Register --}}
    <div class="w-full lg:w-7/12 flex flex-col h-screen relative bg-white overflow-y-auto">

        <div class="flex items-center justify-between p-6 sm:p-8 shrink-0">
            <div class="lg:hidden flex items-center gap-3">
                <div class="w-12 h-12 bg-white border border-slate-100 rounded-xl p-2 shadow-sm">
                    <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="hidden lg:block"></div>

            {{-- Tombol Kembali --}}
            <a href="/" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-all duration-300 group bg-slate-50 hover:bg-indigo-50 px-4 py-2 rounded-full border border-slate-100">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Form Container --}}
        <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 py-2 my-auto w-full max-w-[680px] mx-auto">
            {{-- Header Form --}}
            <div class="mb-5 text-left">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Register Akun Baru</h2>
                <p class="text-sm text-slate-500 font-medium">Lengkapi formulir di bawah ini dengan data Anda untuk mendaftarkan akun baru.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                {{-- Input Nama --}}
                <div>
                    <label for="name" class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>

                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Contoh: Budi Santoso"
                               oninput="this.value = this.value.replace(/[^a-zA-Z\s.,']/g, '')"
                               class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-3 pl-11 pr-4 transition-all duration-200 text-sm font-medium placeholder-slate-400">
                    </div>
                    @error('name') <span class="text-red-500 text-xs mt-1.5 font-medium block">{{ $message }}</span> @enderror
                </div>

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="admin@kampus.ac.id"
                               class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-3 pl-11 pr-4 transition-all duration-200 text-sm font-medium placeholder-slate-400">
                    </div>
                    @error('email') <span class="text-red-500 text-xs mt-1.5 font-medium block">{{ $message }}</span> @enderror
                </div>

                {{-- Input Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-2">Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <input id="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter"
                                   x-bind:type="showPassword ? 'text' : 'password'"
                                   class="block w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-3 pl-11 pr-12 transition-all duration-200 text-sm font-medium placeholder-slate-400">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" aria-label="Toggle password visibility">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.107.424.107.639a1.012 1.012 0 01-.1.639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178a1.012 1.012 0 01-.001-.639z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path></svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1.5 font-medium block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-2">Konfirmasi Password</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password"
                                   x-bind:type="showPassword ? 'text' : 'password'"
                                   class="block w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 py-3 pl-11 pr-12 transition-all duration-200 text-sm font-medium placeholder-slate-400">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors" aria-label="Toggle password visibility">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.107.424.107.639a1.012 1.012 0 01-.1.639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178a1.012 1.012 0 01-.001-.639z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path></svg>
                            </button>
                        </div>
                        @error('password_confirmation') <span class="text-red-500 text-xs mt-1.5 font-medium block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Tombol Action --}}
                <div class="pt-6">
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-3.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 transition-all duration-200 shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5">
                        Register
                    </button>
                </div>

                {{-- Login Link --}}
                <div class="text-center mt-8 pt-6 pb-8 border-t border-slate-100">
                    <p class="text-sm text-slate-600 font-medium">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors ml-1 hover:underline">
                            Login di sini
                        </a>
                    </p>
                </div>

            </form>
        </div>
    </div>

    {{-- Menampilkan Pop Up Registrasi Berhasil dan Redirect ke Login --}}
    @if(session('register_success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Anda Berhasil Melakukan Register Akun',
                    confirmButtonText: 'Ke Halaman Login',
                    confirmButtonColor: '#4f46e5',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            });
        </script>
    @endif

</body>
</html>
