<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $authTitles = [
                'login' => 'Connexion',
                'password.request' => 'Mot de passe oublié',
                'password.reset' => 'Nouveau mot de passe',
                'password.confirm' => 'Confirmation',
                'verification.notice' => 'Vérification de l\'e-mail',
            ];
            $pageTitle = $authTitles[Route::currentRouteName()] ?? null;
        @endphp

        <title>{{ $pageTitle ? $pageTitle . ' — ' . config('app.name', 'ERP') : config('app.name', 'ERP') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Dark mode init (avoid a flash of the wrong theme, keeps parity with the app pages) -->
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased dark:text-slate-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100 dark:bg-slate-950 relative overflow-hidden">

            <div class="pointer-events-none absolute -top-24 -left-24 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl dark:bg-indigo-500/10"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-violet-200/40 blur-3xl dark:bg-violet-500/10"></div>

            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-200/50 dark:shadow-none">
                        <i data-lucide="boxes" class="h-7 w-7"></i>
                    </span>
                    <span class="text-xl font-bold text-slate-800 dark:text-white">{{ config('app.name', 'Laravel') }}</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 sm:px-8 bg-white shadow-[0_20px_60px_-30px_rgba(15,23,42,0.18)] overflow-hidden sm:rounded-[2rem] border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                {{ $slot }}
            </div>

            <p class="relative z-10 mt-6 mb-8 text-xs text-slate-400 dark:text-slate-500">
                {{ config('app.name', 'ERP') }} &middot; Espace réservé au personnel
            </p>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/lucide@0.462.0/dist/umd/lucide.min.js" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => window.lucide && window.lucide.createIcons());
        </script>
    </body>
</html>
