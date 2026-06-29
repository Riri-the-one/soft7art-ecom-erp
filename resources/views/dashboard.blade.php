<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord ERP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900">Bonjour, {{ Auth::user()->name }}</h3>
                <p class="text-gray-600 mt-1">Bienvenue sur votre espace de gestion.</p>
            </div>

            {{-- Vue d'ensemble - Statistiques --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                {{-- Widget: Total Clients --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Clients</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Customer::count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Widget: Commandes du jour --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Commandes du jour</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Widget: Produits en rupture de stock --}}
                @if(in_array(auth()->user()->role, ['super_admin', 'stock_manager']))
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Stock critique</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Product::where('stock_quantity', '<', 10)->count() }}</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Widget: Chiffre d'affaires du mois --}}
                @if(auth()->user()->role === 'super_admin')
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">CA du mois</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format(\App\Models\Order::whereMonth('created_at', now()->month)->sum('total_amount'), 0) }} DH</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- BLOC 1 : Visible par le Directeur uniquement --}}
                @if(Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Espace Direction</h4>
                            <p class="text-gray-600 text-sm mb-4">Vue globale sur le chiffre d'affaires et la gestion des employés.</p>
                        </div>
                        <a href="{{ route('statistics.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gray-900 text-white font-medium rounded-lg text-sm hover:bg-gray-800 transition-colors">
                            Voir les statistiques
                        </a>
                    </div>
                @endif

                {{-- BLOC 2 : Visible par l'Agent et le Directeur --}}
                @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Espace Commercial</h4>
                            <p class="text-gray-600 text-sm mb-4">Gestion du CRM client et suivi des nouvelles commandes.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('orders.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                Gérer les commandes
                            </a>
                            <a href="{{ route('customers.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-blue-600 border border-blue-600 font-medium rounded-lg text-sm hover:bg-blue-50 transition-colors">
                                Annuaire d'appels
                            </a>
                        </div>
                    </div>
                @endif

                {{-- BLOC 3 : Visible par le Gestionnaire et le Directeur --}}
                @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Espace Logistique</h4>
                            <p class="text-gray-600 text-sm mb-4">Mise à jour du catalogue et alertes de stock critique.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('products.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg text-sm hover:bg-green-700 transition-colors">
                                Gérer l'inventaire
                            </a>
                            <a href="{{ route('products.alerts') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-green-600 border border-green-600 font-medium rounded-lg text-sm hover:bg-green-50 transition-colors">
                                Alertes Stock Critique
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>