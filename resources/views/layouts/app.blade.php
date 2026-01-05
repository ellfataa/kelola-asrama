<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <linkpreconnect href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 flex">

            @include('layouts.sidebar')

            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden" style="display: none;"></div>

            <div class="flex-1 flex flex-col min-h-screen overflow-hidden">

                @include('layouts.navigation')

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    @if (isset($header))
                        <header class="bg-white shadow rounded-lg mb-6 p-4">
                            {{ $header }}
                        </header>
                    @endif

                    {{ $slot }}
                </main>
            </div>

        </div>
    </body>
</html>
