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
        </style>
    </head>
    <body class="text-slate-900 antialiased bg-white h-screen flex items-center justify-center relative overflow-hidden">

        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-grid-pattern opacity-[0.4]"></div>
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        </div>

        <div class="w-full sm:max-w-lg px-6 py-8 bg-white/80 backdrop-blur-xl shadow-2xl border border-white/50 sm:rounded-3xl relative z-10 mx-4">

            <div class="flex justify-center mb-6">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                            <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" />
                        </svg>
                    </div>
                </a>
            </div>

            {{ $slot }}

        </div>
    </body>
</html>
