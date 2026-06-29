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