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
            /* Custom Grid Background */
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
<body class="h-full bg-white text-slate-900 antialiased selection:bg-indigo-500 selection:text-white">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <nav class="relative z-50 w-full px-6 py-6 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-2 font-bold text-xl tracking-tight text-slate-800">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"/>
                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"/>
                </svg>
            </div>
            <span>Asrama<span class="text-indigo-600">App</span></span>
        </div>
    </nav>

    <main class="relative z-10 flex flex-col justify-center items-center min-h-[80vh] px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-3xl text-center space-y-8 animate-fade-in-up">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-semibold uppercase tracking-wide mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Sistem Informasi Manajemen
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Kelola Asrama dengan
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Lebih Cerdas</span>
            </h1>

            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                Platform terintegrasi untuk pendaftaran, monitoring penghuni, dan manajemen fasilitas asrama dalam satu dashboard yang modern.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="group relative inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all duration-200 bg-indigo-600 rounded-full hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                            Masuk ke Dashboard
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white transition-all duration-200 bg-slate-900 rounded-full hover:bg-slate-800 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-slate-700 transition-all duration-200 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Registrasi
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </main>

    <footer class="absolute bottom-4 w-full text-center text-slate-400 text-xs">
        &copy; {{ date('Y') }} Sistem Pengelolaan Asrama.
    </footer>

</body>
</html>
