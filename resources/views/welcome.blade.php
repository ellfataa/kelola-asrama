<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Asrama') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        animation: {
                            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                            'blob': 'blob 7s infinite',
                        },
                        keyframes: {
                            fadeInUp: {
                                '0%': { opacity: '0', transform: 'translateY(20px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' },
                            },
                            blob: {
                                '0%': { transform: 'translate(0px, 0px) scale(1)' },
                                '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                                '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                                '100%': { transform: 'translate(0px, 0px) scale(1)' },
                            }
                        }
                    }
                }
            }
        </script>
    @endif
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased selection:bg-indigo-500 selection:text-white relative flex flex-col items-center justify-center overflow-hidden">

    {{-- Notifikasi (Jika Logout) --}}
    @if (session('status'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-5"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-5"
             x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[60] bg-slate-900 text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 border border-slate-700/50 backdrop-blur-md">
            <div class="bg-emerald-500/20 p-1 rounded-full">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-medium">{{ session('status') }}</span>
        </div>
    @endif

    {{-- Background Effects --}}
    <div class="fixed inset-0 z-0 pointer-events-none flex items-center justify-center">
        <div class="absolute inset-0" style="background-image: radial-gradient(rgba(15, 23, 42, 0.04) 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute w-[30rem] h-[30rem] bg-indigo-100/70 rounded-full mix-blend-multiply filter blur-3xl animate-blob -ml-64 -mt-64"></div>
        <div class="absolute w-[30rem] h-[30rem] bg-blue-100/70 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000 ml-64 mt-64"></div>
    </div>

    {{-- Main Content --}}
    <main class="relative z-10 w-full max-w-3xl mx-auto px-6 flex flex-col items-center text-center animate-fade-in-up">
        {{-- Centered Logo --}}
        <div class="w-24 h-24 sm:w-28 sm:h-28 bg-white rounded-[2rem] p-5 shadow-xl shadow-slate-200/50 border border-slate-100 mb-8 transform transition duration-500 hover:-translate-y-1.5 flex items-center justify-center">
            <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-full h-full object-contain">
        </div>

        {{-- Headline Singkat --}}
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
            Kelola Asrama <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Lebih Modern</span>
        </h1>

        {{-- Subheadline Singkat --}}
        <p class="text-base sm:text-lg text-slate-500 max-w-xl mx-auto mb-10 leading-relaxed font-medium">
            Platform terpadu untuk kemudahan pengelolaan fasilitas asrama<br> AMN Cilacap
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full sm:w-auto">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="w-full sm:w-auto group relative inline-flex items-center justify-center px-8 py-3.5 text-sm sm:text-base font-bold text-white transition-all duration-200 bg-indigo-600 rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                        Ke Dashboard Utama
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-sm sm:text-base font-bold text-white transition-all duration-200 bg-indigo-600 rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 uppercase tracking-wide">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-sm sm:text-base font-bold text-slate-700 transition-all duration-200 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 uppercase tracking-wide">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <footer class="absolute bottom-6 w-full text-center px-4">
        <p class="text-slate-400 text-[11px] sm:text-xs font-medium tracking-wide">
            &copy; {{ date('Y') }} Akademi Maritim Nusantara (AMN) Cilacap.
        </p>
    </footer>

    {{-- Script notifikasi hapus akun --}}
    @if (session('status') === 'account-deleted')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Akun Anda Berhasil Dihapus.',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'Tutup'
                });
            });
        </script>
    @endif

</body>
</html>
