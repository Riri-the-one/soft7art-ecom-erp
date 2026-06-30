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
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white/80 uppercase tracking-wider mb-2">Total Clients</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ $totalClients }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Widget: Commandes du jour --}}
                <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Commandes du jour</p>
                            <p class="text-2xl font-bold text-gray-700 mt-1">{{ $ordersToday }}</p>
                        </div>
                        <div class="p-3 bg-teal-100 rounded-full">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Widget: Produits en rupture de stock --}}
                @if(in_array(auth()->user()->role, ['super_admin', 'stock_manager']))
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Stock critique</p>
                                <p class="text-2xl font-bold text-gray-700 mt-1">{{ $lowStockProducts }}</p>
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
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">CA du mois</p>
                                <p class="text-2xl font-bold text-gray-700 mt-1">{{ number_format($monthlyRevenue ?? 0, 0) }} DH</p>
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

            {{-- Super Admin Specific Widgets --}}
            @if(auth()->user()->role === 'super_admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Monthly Goal Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Objectif Mensuel (50 000 DH)</h4>
                        <div class="mb-2">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-400">Progression</span>
                                <span class="text-purple-600 font-bold">{{ number_format($monthlyGoal, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-purple-500 to-teal-400 h-3 rounded-full" style="width: {{ $monthlyGoal }}%"></div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 mt-3">{{ number_format($monthlyRevenue, 0) }} DH réalisés ce mois</p>
                    </div>

                    {{-- Top 5 Products Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Top 5 Produits</h4>
                        <div class="space-y-3">
                            @foreach($topProducts as $index => $product)
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full text-xs font-bold">{{ $index + 1 }}</span>
                                        <span class="text-gray-800 font-medium">{{ $product->name }}</span>
                                    </div>
                                    <span class="text-sm text-gray-400">{{ $product->stock_quantity }} en stock</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Recent Activity Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Dernières Activités</h4>
                        <div class="space-y-3">
                            @foreach($recentActivity as $activity)
                                <div class="py-2 border-b border-gray-50 last:border-0">
                                    <p class="text-gray-800 font-medium">{{ $activity->customer->name }}</p>
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-sm text-gray-400">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="text-purple-600 font-bold text-sm">{{ number_format($activity->total_amount, 0) }} DH</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Agent Specific Widgets --}}
            @if(auth()->user()->role === 'agent')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Success Rate Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Taux de Succès</h4>
                        <div class="text-center">
                            <p class="text-4xl text-purple-600 font-bold">{{ $agentSuccessRate }}%</p>
                            <p class="text-sm text-gray-400 mt-2">Commandes livrées ou confirmées</p>
                        </div>
                    </div>

                    {{-- Recent Customers Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Derniers Clients Acquis</h4>
                        <div class="space-y-2">
                            @foreach($recentCustomers as $customer)
                                <div class="hover:bg-gray-50 rounded-lg p-2 transition">
                                    <p class="text-gray-800 font-medium">{{ $customer->name }}</p>
                                    <p class="text-sm text-gray-400">{{ $customer->email }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stock Manager Specific Widgets --}}
            @if(auth()->user()->role === 'stock_manager')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Inventory Value Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Valeur Totale du Stock</h4>
                        <div class="text-center">
                            <p class="text-4xl text-teal-500 font-bold">{{ number_format($inventoryValue, 0) }} DH</p>
                            <p class="text-sm text-gray-400 mt-2">Valeur d'achat totale</p>
                        </div>
                    </div>

                    {{-- Critical Stock Widget --}}
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 border-none">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Ruptures Imminentes (Stock < 10)</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs uppercase text-gray-400 font-semibold pb-2">Produit</th>
                                        <th class="text-left text-xs uppercase text-gray-400 font-semibold pb-2">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalStock as $product)
                                        <tr class="border-b border-gray-50 last:border-0">
                                            <td class="py-2 text-gray-800">{{ $product->name }}</td>
                                            <td class="py-2 text-red-500 font-medium">{{ $product->stock_quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- BLOC 1 : Visible par le Directeur uniquement --}}
                @if(Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Espace Direction</h4>
                            <p class="text-gray-700 text-sm mb-4">Vue globale sur le chiffre d'affaires et la gestion des employés.</p>
                        </div>
                        <a href="{{ route('statistics.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-teal-400 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                            Voir les statistiques
                        </a>
                    </div>
                @endif

                {{-- BLOC 2 : Visible par l'Agent et le Directeur --}}
                @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Espace Commercial</h4>
                            <p class="text-gray-700 text-sm mb-4">Gestion du CRM client et suivi des nouvelles commandes.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('orders.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-teal-400 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                                Gérer les commandes
                            </a>
                            <a href="{{ route('customers.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-purple-600 border border-purple-200 font-medium rounded-lg text-sm hover:bg-purple-50 transition-colors">
                                Annuaire d'appels
                            </a>
                        </div>
                    </div>
                @endif

                {{-- BLOC 3 : Visible par le Gestionnaire et le Directeur --}}
                @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Espace Logistique</h4>
                            <p class="text-gray-700 text-sm mb-4">Mise à jour du catalogue et alertes de stock critique.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('products.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-teal-400 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                                Gérer l'inventaire
                            </a>
                            <a href="{{ route('products.alerts') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-teal-600 border border-teal-200 font-medium rounded-lg text-sm hover:bg-teal-50 transition-colors">
                                Alertes Stock Critique
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>