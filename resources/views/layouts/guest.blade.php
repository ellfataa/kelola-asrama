<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg-grid-pattern {
                background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px),
                                  linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
                background-size: 40px 40px;
                mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
                -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            }
            body { font-family: 'Inter', sans-serif; }

            /* Animasi halus untuk background blobs */
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
        </style>
    </head>
    <body class="text-slate-900 antialiased bg-white h-screen flex items-center justify-center relative overflow-hidden">

        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-grid-pattern opacity-[0.4]"></div>
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob" style="animation-delay: 2s"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob" style="animation-delay: 4s"></div>
        </div>

        <div class="w-full sm:max-w-lg px-8 py-10 bg-white/80 backdrop-blur-xl shadow-2xl border border-white/50 sm:rounded-3xl relative z-10 mx-4 transition-all duration-300 hover:shadow-indigo-500/10">

            <div class="flex justify-center mb-8">
                <a href="/" class="group relative">
                    <div class="absolute inset-0 bg-indigo-500 rounded-2xl blur-lg opacity-20 group-hover:opacity-40 transition duration-500"></div>

                    <div class="relative w-24 h-24 bg-white rounded-2xl shadow-lg border border-slate-100 flex items-center justify-center transform group-hover:scale-105 transition duration-300">
                        <img src="{{ asset('assets/images/logo-amn.webp') }}"
                             alt="Logo AMN"
                             class="w-16 h-16 object-contain drop-shadow-sm">
                    </div>
                </a>
            </div>

            {{ $slot }}

        </div>
    </body>
</html>
