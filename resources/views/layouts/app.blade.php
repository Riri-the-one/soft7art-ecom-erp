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
    <body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-gray-900 dark:text-gray-100">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-100 font-sans antialiased text-slate-900 dark:bg-gray-900 dark:text-gray-100">
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/40 md:hidden" @click="sidebarOpen = false"></div>

            @include('layouts.navigation')

            <div class="flex-1 flex flex-col overflow-hidden">
                <div class="md:hidden bg-white/95 border-b border-slate-200 backdrop-blur">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button @click="sidebarOpen = true" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white/90 px-3 py-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-purple-500/50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</a>
                    </div>
                </div>

                @isset($header)
                    <header class="bg-gradient-to-r from-purple-600 via-teal-500 to-cyan-400 shadow-xl">
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
