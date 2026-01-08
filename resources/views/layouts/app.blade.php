<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Asrama') }}</title>

        {{-- FIREBASE --}}
        <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>


        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            /* Custom Scrollbar untuk tampilan lebih rapi */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">

        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-slate-50">

            @include('layouts.sidebar')

            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                 class="fixed inset-0 bg-slate-900/50 z-30 md:hidden backdrop-blur-sm transition-opacity"
                 style="display: none;"></div>

            <div class="flex-1 flex flex-col min-h-screen overflow-hidden transition-all duration-300">

                @include('layouts.navigation')

                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
                    @if (isset($header))
                        <header class="bg-white/80 backdrop-blur-md shadow-sm border border-slate-200 rounded-2xl mb-6 p-4 sm:flex sm:justify-between sm:items-center">
                            {{ $header }}
                        </header>
                    @endif

                    {{ $slot }}
                </main>
            </div>

        </div>

        <!-- Custom Firebase JS -->
        <script src="{{ asset('js/firebase.js') }}"></script>
    </body>
</html>
