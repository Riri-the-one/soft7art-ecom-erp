<nav
    :class="[sidebarCollapsed ? 'w-20' : 'w-72', sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0']"
    class="fixed inset-y-0 left-0 z-50 flex h-full flex-col border-r border-slate-200 bg-white text-slate-700 transition-all duration-200 md:static md:z-auto dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
>
    <!-- Haut : Logo et Liens -->
    <div class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">
        <!-- Logo Section -->
        <div class="flex items-center gap-3 px-5 py-6" :class="sidebarCollapsed ? 'justify-center px-2' : ''">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/30">
                    <i data-lucide="boxes" class="h-5 w-5"></i>
                </span>
                <span x-show="!sidebarCollapsed" x-transition class="text-lg font-semibold text-slate-900 dark:text-white truncate">{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>

        <!-- Action Button -->
        @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
            <div class="px-4 pb-4" :class="sidebarCollapsed ? 'px-2' : ''">
                <a href="{{ route('orders.create') }}" class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-900/20 dark:shadow-indigo-900/40 transition hover:shadow-lg" :class="sidebarCollapsed ? 'px-0' : 'px-4'">
                    <i data-lucide="plus" class="h-4 w-4 shrink-0"></i>
                    <span x-show="!sidebarCollapsed" x-transition class="truncate">Nouvelle Commande</span>
                </a>
            </div>
        @endif

        <!-- Navigation Links -->
        <div class="flex flex-col gap-1 px-3">
            @php
                $lowStockCount = \App\Models\Product::where('stock_quantity', '<', 5)->count();
            @endphp

            <a href="{{ route('dashboard') }}"
               class="group flex items-center gap-3 rounded-xl border-l-2 px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-500/15 dark:text-white' : 'border-transparent text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''">
                <i data-lucide="layout-dashboard" class="h-5 w-5 shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition class="truncate">{{ __('Dashboard') }}</span>
            </a>

            <div class="relative">
                <a href="{{ route('products.index') }}"
                   class="group flex items-center gap-3 rounded-xl border-l-2 px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('products.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-500/15 dark:text-white' : 'border-transparent text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''">
                    <i data-lucide="package" class="h-5 w-5 shrink-0"></i>
                    <span x-show="!sidebarCollapsed" x-transition class="truncate flex-1">{{ __('Catalogue') }}</span>
                    @if($lowStockCount > 0)
                        <span x-show="!sidebarCollapsed" x-transition class="inline-flex items-center rounded-full bg-red-500 px-2 py-0.5 text-xs font-semibold text-white">{{ $lowStockCount }}</span>
                    @endif
                </a>
                @if($lowStockCount > 0)
                    <span x-show="sidebarCollapsed" class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                @endif
            </div>

            @can('viewAny', \App\Models\Order::class)
            <a href="{{ route('orders.index') }}"
               class="group flex items-center gap-3 rounded-xl border-l-2 px-3 py-2.5 text-sm font-medium transition-colors {{ (request()->routeIs('orders.index') || request()->routeIs('orders.show')) ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-500/15 dark:text-white' : 'border-transparent text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''">
                <i data-lucide="shopping-cart" class="h-5 w-5 shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition class="truncate">{{ __('Commandes') }}</span>
            </a>

            @endcan
            @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('agent'))
                <a href="{{ route('customers.index') }}"
                   class="group flex items-center gap-3 rounded-xl border-l-2 px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('customers.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-500/15 dark:text-white' : 'border-transparent text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''">
                    <i data-lucide="users" class="h-5 w-5 shrink-0"></i>
                    <span x-show="!sidebarCollapsed" x-transition class="truncate">{{ __('Annuaire Clients') }}</span>
                </a>
            @endif

            @if(Auth::user()->hasRole('super_admin'))
                <a href="{{ route('statistics.index') }}"
                   class="group flex items-center gap-3 rounded-xl border-l-2 px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('statistics.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-500/15 dark:text-white' : 'border-transparent text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''">
                    <i data-lucide="bar-chart-3" class="h-5 w-5 shrink-0"></i>
                    <span x-show="!sidebarCollapsed" x-transition class="truncate">{{ __('Statistiques') }}</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Bas : réduire la sidebar -->
    <div class="border-t border-slate-200 p-3 dark:border-slate-800">
        <button @click="toggleCollapsed()" type="button" class="hidden md:flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
            <i data-lucide="panel-left-close" class="h-5 w-5 shrink-0" x-show="!sidebarCollapsed"></i>
            <i data-lucide="panel-left-open" class="h-5 w-5 shrink-0" x-show="sidebarCollapsed"></i>
            <span x-show="!sidebarCollapsed" x-transition class="truncate">Réduire</span>
        </button>
    </div>
</nav>
