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
        <style>
            .bg-grid-pattern {
                background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px),
                                  linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
                background-size: 40px 40px;
                mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
                -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            }
        </style>
    @endif
</head>
<body class="h-full bg-white text-slate-900 antialiased selection:bg-indigo-500 selection:text-white relative">

    {{-- Toast Notification (Jika Logout) --}}
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
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Navbar --}}
    <nav class="relative z-50 w-full px-6 py-8 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-4 group cursor-default">
            {{-- Logo Container Besar --}}
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/10 border border-slate-100 transform group-hover:scale-105 transition duration-300">
                <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-9 h-9 object-contain">
            </div>

            {{-- Brand Text --}}
            <div class="flex flex-col">
                <span class="font-extrabold text-2xl tracking-tight text-slate-900 leading-none">Asrama AMN</span>
                <span class="text-xs font-bold text-indigo-600 tracking-[0.2em] uppercase mt-1">Cilacap</span>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="relative z-10 flex flex-col justify-center items-center min-h-[75vh] px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl text-center space-y-8 animate-fade-in-up">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-indigo-100 shadow-sm text-indigo-600 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                </span>
                Sistem Informasi Manajemen
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Kelola Asrama dengan
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Lebih Cerdas</span>
            </h1>

            {{-- Subheadline --}}
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                Platform terintegrasi untuk pendaftaran, monitoring penghuni, dan manajemen fasilitas asrama dalam satu dashboard yang modern.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="group relative inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-indigo-600 rounded-2xl hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                            Masuk ke Dashboard
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-slate-900 rounded-2xl hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold text-slate-700 transition-all duration-200 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Registrasi
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </main>

    <footer class="absolute bottom-6 w-full text-center text-slate-400 text-xs font-medium">
        &copy; {{ date('Y') }} Sistem Pengelolaan Asrama AMN Cilacap.
    </footer>

</body>
</html>
