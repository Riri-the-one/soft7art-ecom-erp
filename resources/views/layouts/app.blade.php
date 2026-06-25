<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 font-sans antialiased text-gray-900">
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40 md:hidden" @click="sidebarOpen = false"></div>

            @include('layouts.navigation')

            <div class="flex-1 flex flex-col overflow-hidden">
                <div class="md:hidden bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button @click="sidebarOpen = true" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="font-semibold text-gray-900">{{ config('app.name', 'Laravel') }}</a>
                    </div>
                </div>

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
