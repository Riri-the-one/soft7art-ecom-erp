        <nav class="w-72 bg-white/95 border border-slate-200 shadow-[0_25px_50px_-25px_rgba(15,23,42,0.25)] h-full flex flex-col backdrop-blur">
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
            <div class="p-4 border-t border-slate-200 mt-auto">
                <div class="text-sm text-slate-700">{{ Auth::user()->name }}</div>
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
