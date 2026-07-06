        <nav class="w-72 bg-white/95 border border-slate-200 shadow-[0_25px_50px_-25px_rgba(15,23,42,0.25)] h-full flex flex-col backdrop-blur dark:bg-gray-900 dark:text-gray-100">
            <!-- Haut : Logo et Liens -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                <!-- Logo Section -->
                <div class="px-6 py-6 border-b border-slate-200">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-10 w-auto fill-current text-purple-600" />
                        <span class="text-lg font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <!-- Action Button -->
                @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
                    <div class="px-5 py-5">
                        <a href="{{ route('orders.create') }}" class="block w-full text-center bg-gradient-to-r from-purple-600 to-teal-400 text-white rounded-2xl py-3 shadow-lg font-semibold text-sm transition hover:shadow-xl">
                            Nouvelle Commande
                        </a>
                    </div>
                @endif

                <!-- Navigation Links -->
                <div class="flex flex-col space-y-1 mt-2 px-4">
                    @php
                        $lowStockCount = \App\Models\Product::where('stock_quantity', '<=', 5)->count();
                    @endphp

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="w-full block">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <div class="flex items-center justify-between gap-2">
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="flex-1">
                            {{ __('Catalogue') }}
                        </x-nav-link>
                        @if($lowStockCount > 0)
                            <a href="{{ route('products.index', ['stock' => 'critical']) }}" class="inline-flex items-center rounded-full bg-red-600 px-2.5 py-0.5 text-xs font-semibold text-white hover:bg-red-700 transition">
                                {{ $lowStockCount }}
                            </a>
                        @endif
                    </div>

                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index') || request()->routeIs('orders.show')" class="w-full block">
                        {{ __('Commandes') }}
                    </x-nav-link>


                    @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('agent'))
                        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="w-full block">
                            {{ __('Annuaire Clients') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->hasRole('super_admin'))
                        <x-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')" class="w-full block">
                            {{ __('Statistiques') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Bas : Profile Section -->
            <div class="p-4 border-t border-slate-200 mt-auto dark:border-gray-700">
                <button id="theme-toggle" type="button" class="mt-4 mb-4 w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-medium text-slate-700 transition-colors duration-200 hover:bg-slate-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 flex items-center justify-between">
                    <span class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-purple-500 to-teal-400 text-white shadow-sm">
                            <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        </span>
                        <span id="theme-toggle-text">Mode Sombre</span>
                    </span>
                    <span class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border border-slate-300 bg-white transition-colors duration-200 dark:border-gray-600 dark:bg-slate-700">
                        <span id="theme-toggle-thumb" class="absolute left-1 top-1 h-4 w-4 rounded-full bg-slate-700 shadow transition-transform duration-200 dark:bg-white"></span>
                    </span>
                </button>

                <div class="text-sm text-slate-700 dark:text-slate-300">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-400 mb-4">{{ Auth::user()->email }}</div>

                <x-responsive-nav-link :href="route('profile.edit')" class="block mb-3 text-slate-700 hover:text-purple-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </nav>
