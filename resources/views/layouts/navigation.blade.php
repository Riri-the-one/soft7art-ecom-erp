        <nav class="w-64 bg-white border-r border-gray-200 h-full flex flex-col">
            <!-- Haut : Logo et Liens -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                <!-- Logo Section -->
                <div class="px-6 py-5 border-b border-gray-200">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        <span class="text-lg font-semibold text-gray-900">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-col space-y-2 mt-4 px-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="w-full block">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="w-full block">
                        {{ __('Catalogue') }}
                    </x-nav-link>

                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="w-full block">
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
            <div class="p-4 border-t border-gray-200 bg-gray-50 mt-auto">
                <div class="text-sm text-gray-600">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-400 mb-4">{{ Auth::user()->email }}</div>

                <x-responsive-nav-link :href="route('profile.edit')" class="block mb-3">
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
