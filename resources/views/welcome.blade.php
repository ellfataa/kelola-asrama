<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Sistem Asrama') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                @keyframes float {
                    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
                    10% { opacity: 0.3; }
                    90% { opacity: 0.3; }
                    100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
                }

                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(30px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                @keyframes scaleIn {
                    from { opacity: 0; transform: scale(0.9); }
                    to { opacity: 1; transform: scale(1); }
                }

                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                }

                .particle {
                    position: absolute;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                    animation: float linear infinite;
                }

                .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-duration: 20s; }
                .particle:nth-child(2) { width: 60px; height: 60px; left: 25%; animation-duration: 25s; animation-delay: 2s; }
                .particle:nth-child(3) { width: 100px; height: 100px; left: 50%; animation-duration: 30s; animation-delay: 4s; }
                .particle:nth-child(4) { width: 70px; height: 70px; left: 70%; animation-duration: 22s; animation-delay: 1s; }
                .particle:nth-child(5) { width: 90px; height: 90px; left: 85%; animation-duration: 28s; animation-delay: 3s; }

                .animate-fade-in-up { animation: fadeInUp 0.8s ease-out both; }
                .animate-scale-in { animation: scaleIn 0.6s ease-out; }
                .animate-pulse-slow { animation: pulse 3s ease-in-out infinite; }

                .delay-200 { animation-delay: 0.2s; }
                .delay-400 { animation-delay: 0.4s; }
                .delay-600 { animation-delay: 0.6s; }

                .btn-shimmer::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                    transition: left 0.5s;
                }

                .btn-shimmer:hover::before {
                    left: 100%;
                }
            </style>
        @endif
    </head>
    <body class="bg-gradient-to-br from-purple-500 via-purple-600 to-purple-800 min-h-screen overflow-x-hidden">

        <!-- Animated Background Particles -->
        <div class="fixed inset-0 pointer-events-none">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Main Content -->
        <main class="relative z-10 min-h-screen flex flex-col items-center justify-center px-6 py-8">
            <div class="max-w-5xl w-full text-center">

                <!-- Logo -->
                <div class="mb-10 animate-scale-in">
                    <svg class="w-24 h-24 md:w-32 md:h-32 mx-auto drop-shadow-2xl animate-pulse-slow"
                         viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z"
                              stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 22V12H15V22"
                              stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white mb-5 animate-fade-in-up delay-200 leading-tight drop-shadow-lg">
                    Sistem Pengelolaan Asrama
                </h1>

                <!-- Subtitle -->
                <p class="text-lg md:text-xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed animate-fade-in-up delay-400">
                    Platform modern untuk manajemen dan monitoring asrama secara efisien dan terorganisir
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-600">
                    @guest
                        <a href="{{ route('login') }}"
                           class="relative overflow-hidden btn-shimmer inline-flex items-center justify-center px-10 py-4 bg-white text-slate-800 font-bold text-lg rounded-2xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="relative overflow-hidden btn-shimmer inline-flex items-center justify-center px-10 py-4 bg-white/10 backdrop-blur-lg text-white font-bold text-lg rounded-2xl border-2 border-white/30 shadow-2xl hover:bg-white/20 hover:shadow-3xl hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                            Registrasi
                        </a>
                    @else
                        <a href="{{ url('/dashboard') }}"
                           class="relative overflow-hidden btn-shimmer inline-flex items-center justify-center px-10 py-4 bg-white text-blue-600 font-bold text-lg rounded-2xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                            Buka Dashboard
                        </a>
                    @endguest
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 py-8 text-center text-white/75 text-sm">
            <p>&copy; {{ date('Y') }} Sistem Pengelolaan Asrama. All rights reserved.</p>
        </footer>

    </body>
</html>
