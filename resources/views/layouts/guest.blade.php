<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Mitra Portal</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-grid {
                background-image: radial-gradient(rgba(15, 23, 42, 0.04) 1px, transparent 1px);
                background-size: 24px 24px;
            }
        </style>
    </head>
    <body class="text-slate-900 antialiased bg-white min-h-screen flex overflow-x-hidden">

        {{-- KOLOM KIRI --}}
        <div class="hidden lg:flex lg:w-5/12 bg-slate-50/80 relative flex-col items-center justify-center p-8 text-center border-r border-slate-100">

            {{-- Background Ornamen --}}
            <div class="absolute inset-0 bg-grid"></div>
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-100/70 rounded-full mix-blend-multiply filter blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-100/70 rounded-full mix-blend-multiply filter blur-3xl"></div>

            {{-- Konten Utama --}}
            <div class="relative z-10 flex flex-col items-center">
                <p class="text-indigo-600 text-[10px] font-extrabold tracking-widest uppercase mb-6">
                    Portal Mitra Asrama
                </p>

                <div class="w-28 h-28 bg-white rounded-3xl p-5 shadow-xl shadow-slate-200/50 border border-slate-100 mb-6 transform transition duration-500 hover:-translate-y-1.5">
                    <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-full h-full object-contain">
                </div>

                <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">
                    Akademi Maritim Nusantara<br>Cilacap
                </h1>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="w-full lg:w-7/12 flex items-center justify-center p-6 relative bg-white">

            {{-- Logo Mobile --}}
            <div class="absolute top-5 left-5 lg:hidden flex items-center gap-3 z-20">
                <div class="w-10 h-10 bg-white border border-slate-100 rounded-xl p-1.5 shadow-sm">
                    <img src="{{ asset('assets/images/logo-amn.webp') }}" alt="Logo AMN" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="w-full max-w-md mt-10 lg:mt-0 relative z-10">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>
