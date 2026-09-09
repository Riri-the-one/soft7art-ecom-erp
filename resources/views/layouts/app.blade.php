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

        <!-- Dark mode init (avoid a flash of the wrong theme) -->
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-gray-100">
        @php
            // Notifications "live" adaptées au rôle : pas de table dédiée, on interroge
            // directement les données pertinentes pour le job de chacun.
            $notifications = collect();

            if (Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin')) {
                $lowStockProducts = \App\Models\Product::where('stock_quantity', '<', 10)
                    ->orderBy('stock_quantity')
                    ->limit(5)
                    ->get();

                foreach ($lowStockProducts as $product) {
                    $critical = $product->stock_quantity < 5;
                    $notifications->push([
                        'icon' => 'package-x',
                        'badgeClass' => $critical
                            ? 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                            : 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                        'title' => $critical ? 'Rupture de stock imminente' : 'Stock faible',
                        'subtitle' => $product->name . ' — ' . $product->stock_quantity . ' unité(s) restante(s)',
                        'time' => $product->updated_at,
                        'url' => route('products.edit', $product),
                    ]);
                }
            }

            if (Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin')) {
                $pendingOrders = \App\Models\Order::where('status', 'pending')
                    ->with('customer')
                    ->latest()
                    ->limit(5)
                    ->get();

                foreach ($pendingOrders as $order) {
                    $notifications->push([
                        'icon' => 'shopping-cart',
                        'badgeClass' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                        'title' => 'Commande en attente',
                        'subtitle' => 'Commande #' . $order->id . ' — ' . ($order->customer->name ?? 'Client'),
                        'time' => $order->created_at,
                        'url' => route('orders.show', $order),
                    ]);
                }
            }

            $notifications = $notifications->sortByDesc('time')->values();
            $notificationCount = $notifications->count();
        @endphp
        <div
            x-data="{
                sidebarOpen: false,
                sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
                toggleCollapsed() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                    localStorage.setItem('sidebar-collapsed', this.sidebarCollapsed);
                }
            }"
            class="flex h-screen overflow-hidden bg-slate-100 dark:bg-slate-950"
        >
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/50 md:hidden" @click="sidebarOpen = false"></div>

            @include('layouts.navigation')

            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Topbar -->
                <header class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-100 md:hidden dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>

                        <div class="relative hidden sm:block">
                            <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                            <input type="text" placeholder="Rechercher..." class="w-56 lg:w-72 rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-sm text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <button id="theme-toggle" type="button" class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                            <i id="theme-toggle-dark-icon" data-lucide="moon" class="hidden h-5 w-5"></i>
                            <i id="theme-toggle-light-icon" data-lucide="sun" class="hidden h-5 w-5"></i>
                        </button>

                        <div class="relative" x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false" type="button" class="relative inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                <i data-lucide="bell" class="h-5 w-5"></i>
                                @if($notificationCount > 0)
                                    <span class="absolute right-0.5 top-0.5 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                                @endif
                            </button>

                            <div x-show="notifOpen" x-transition style="display: none;" class="absolute right-0 z-50 mt-2 w-80 max-w-[90vw] rounded-xl border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                                    <span class="text-sm font-semibold text-slate-800 dark:text-gray-100">Notifications</span>
                                    @if($notificationCount > 0)
                                        <span class="text-xs text-slate-400">{{ $notificationCount }} alerte{{ $notificationCount > 1 ? 's' : '' }}</span>
                                    @endif
                                </div>

                                <div class="max-h-80 overflow-y-auto">
                                    @forelse($notifications as $notif)
                                        <a href="{{ $notif['url'] }}" class="flex items-start gap-3 border-b border-slate-100 px-4 py-3 last:border-0 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">
                                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $notif['badgeClass'] }}">
                                                <i data-lucide="{{ $notif['icon'] }}" class="h-4 w-4"></i>
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block text-sm font-medium text-slate-800 dark:text-gray-100">{{ $notif['title'] }}</span>
                                                <span class="block truncate text-xs text-slate-500 dark:text-slate-400">{{ $notif['subtitle'] }}</span>
                                                <span class="mt-0.5 block text-xs text-slate-400">{{ $notif['time']->diffForHumans() }}</span>
                                            </span>
                                        </a>
                                    @empty
                                        <div class="px-4 py-8 text-center">
                                            <i data-lucide="check-circle" class="mx-auto h-6 w-6 text-slate-300 dark:text-slate-600"></i>
                                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Aucune alerte pour le moment</p>
                                        </div>
                                    @endforelse
                                </div>

                                @if($notificationCount > 0)
                                    <div class="space-y-1 border-t border-slate-100 px-4 py-2 dark:border-slate-800">
                                        @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                                            <a href="{{ route('products.alerts') }}" class="block py-0.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Voir toutes les alertes de stock</a>
                                        @endif
                                        @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
                                            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="block py-0.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Voir toutes les commandes en attente</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="hidden sm:block h-8 w-px bg-slate-200 dark:bg-slate-700"></div>

                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" @click.outside="userMenuOpen = false" type="button" class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:block text-left">
                                    <span class="block text-sm font-semibold text-slate-800 dark:text-gray-100">{{ Auth::user()->name }}</span>
                                    <span class="block text-xs text-slate-400 capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                                </span>
                                <i data-lucide="chevron-down" class="hidden sm:block h-4 w-4 text-slate-400"></i>
                            </button>

                            <div x-show="userMenuOpen" x-transition style="display: none;" class="absolute right-0 z-50 mt-2 w-48 rounded-xl border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-700 dark:bg-slate-900">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-gray-200 dark:hover:bg-slate-800">
                                    <i data-lucide="user" class="h-4 w-4"></i> Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-slate-800">
                                        <i data-lucide="log-out" class="h-4 w-4"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                @isset($header)
                    <div class="border-b border-slate-200 bg-white px-4 py-4 sm:px-6 lg:px-8 dark:border-slate-800 dark:bg-slate-900">
                        {{ $header }}
                    </div>
                @endisset

                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/lucide@0.462.0/dist/umd/lucide.min.js" defer></script>
        <script>
            function renderIcons() {
                if (window.lucide) window.lucide.createIcons();
            }
            document.addEventListener('DOMContentLoaded', renderIcons);
            document.addEventListener('alpine:initialized', renderIcons);
            window.addEventListener('load', renderIcons);

            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            function setTheme(isDark) {
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                    if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
                    if (themeToggleDarkIcon) themeToggleDarkIcon.classList.add('hidden');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                    if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
                    if (themeToggleLightIcon) themeToggleLightIcon.classList.add('hidden');
                }
            }

            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var storedTheme = localStorage.getItem('color-theme');
            var darkMode = storedTheme === 'dark' || (!storedTheme && prefersDark);
            setTheme(darkMode);

            var themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    setTheme(!document.documentElement.classList.contains('dark'));
                });
            }
        </script>
    </body>
</html>
