<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord ERP') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gradient-to-r from-purple-500 to-teal-400 rounded-xl shadow-lg p-8 mb-8 text-white w-full">
                <h3 class="text-3xl font-bold mb-2">Bonjour, {{ auth()->user()->name }}</h3>
                <p class="text-purple-50 text-lg">Votre ERP est prêt à piloter les ventes, la logistique et le suivi client avec une expérience moderne et fluide.</p>
            </div>

            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'agent')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <a href="{{ route('customers.index') }}" class="group block w-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all p-6 text-white border-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider mb-1">Total Clients</p>
                            <h4 class="text-3xl font-bold">{{ $totalCustomers ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('orders.index') }}" class="group block w-full bg-white rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all p-6 border border-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-gray-400">Commandes du jour</p>
                            <h4 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $ordersToday ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-teal-50 rounded-lg group-hover:bg-teal-100 transition-colors dark:bg-teal-900/20 dark:group-hover:bg-teal-900/30">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('products.index', ['stock' => 'critical']) }}" class="group block w-full bg-white rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all p-6 border border-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-gray-400">Stock critique</p>
                            <h4 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $criticalStock ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg group-hover:bg-red-100 transition-colors dark:bg-red-900/20 dark:group-hover:bg-red-900/30">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                </a>

                <div class="w-full bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-gray-400">CA du mois</p>
                            <h4 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($monthlyRevenue ?? 0, 0, ',', ' ') }} DH</h4>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg dark:bg-purple-900/20">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
                
            </div>
            @endif

            {{-- Super Admin Specific Widgets --}}
            @if(auth()->user()->role === 'super_admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Monthly Goal Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Objectif Mensuel (50 000 DH)</h4>
                        <div class="mb-2">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500 dark:text-slate-400">Progression</span>
                                <span class="text-purple-600 font-bold">{{ number_format($monthlyGoal, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-purple-500 to-teal-400 h-3 rounded-full" style="width: {{ $monthlyGoal }}%"></div>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 mt-3">{{ number_format($monthlyRevenue, 0) }} DH réalisés ce mois</p>
                    </div>

                    {{-- Top 5 Products Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Top 5 Produits</h4>
                        <div class="space-y-3">
                            @foreach($topProducts as $index => $product)
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full text-xs font-bold">{{ $index + 1 }}</span>
                                        <span class="text-slate-900 font-medium dark:text-gray-100">{{ $product->name }}</span>
                                    </div>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ $product->stock_quantity }} en stock</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Recent Activity Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Dernières Activités</h4>
                        <div class="space-y-3">
                            @foreach($recentActivity as $activity)
                                <a href="{{ route('orders.show', $activity) }}" class="block rounded-3xl py-2 border-b border-slate-200 last:border-0 transition hover:bg-slate-50 hover:shadow-sm dark:border-gray-700 dark:hover:bg-slate-800">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-slate-900 font-medium dark:text-gray-100">{{ $activity->customer->name }}</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">Commande #{{ $activity->id }}</p>
                                        </div>
                                        <span class="text-purple-600 font-bold text-sm">{{ number_format($activity->total_amount, 0) }} DH</span>
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $activity->created_at->format('d/m/Y H:i') }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Agent Specific Widgets --}}
            @if(auth()->user()->role === 'agent')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 auto-rows-fr">
                    {{-- Success Rate Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Taux de Succès</h4>
                        <div class="text-center">
                            <p class="text-4xl text-purple-600 font-bold">{{ $agentSuccessRate }}%</p>
                            <p class="text-sm text-slate-500 mt-2">Commandes livrées ou confirmées</p>
                        </div>
                    </div>

                    {{-- Recent Customers Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 md:col-span-2 h-full flex flex-col dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Derniers Clients Acquis</h4>
                        <div class="space-y-2">
                            @foreach($recentCustomers as $customer)
                                <div class="hover:bg-slate-50 rounded-lg p-2 transition dark:hover:bg-slate-800">
                                    <p class="text-slate-900 font-medium dark:text-gray-100">{{ $customer->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $customer->email }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stock Manager Specific Widgets --}}
            @if(auth()->user()->role === 'stock_manager')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 auto-rows-fr">
                    {{-- Inventory Value Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Valeur Totale du Stock</h4>
                        <div class="text-center">
                            <p class="text-4xl text-teal-500 font-bold">{{ number_format($inventoryValue, 0) }} DH</p>
                            <p class="text-sm text-slate-500 mt-2">Valeur d'achat totale</p>
                        </div>
                    </div>

                    {{-- Critical Stock Widget --}}
                    <div class="bg-slate-50/70 rounded-3xl shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-950/80 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-slate-900 mb-4 dark:text-gray-100">Ruptures Imminentes (Stock < 10)</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs uppercase text-slate-500 font-semibold pb-2">Produit</th>
                                        <th class="text-left text-xs uppercase text-slate-500 font-semibold pb-2">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalStock as $product)
                                        <tr class="border-b border-slate-200 last:border-0">
                                            <td class="py-2 text-slate-900">{{ $product->name }}</td>
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
                    <div class="bg-slate-50/80 rounded-3xl border border-slate-200 p-6 shadow-[0_25px_50px_-25px_rgba(15,23,42,0.2)] hover:shadow-[0_28px_60px_-30px_rgba(15,23,42,0.22)] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between dark:bg-slate-950/70 dark:border-gray-700">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2 dark:text-slate-400">Espace Direction</h4>
                            <p class="text-slate-700 text-sm mb-4 dark:text-slate-300">Vue globale sur le chiffre d'affaires et la gestion des employés.</p>
                        </div>
                        <a href="{{ route('statistics.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-teal-400 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                            Voir les statistiques
                        </a>
                    </div>
                @endif

                {{-- BLOC 2 : Visible par l'Agent et le Directeur --}}
                @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-slate-50/80 rounded-3xl border border-slate-200 p-6 shadow-[0_25px_50px_-25px_rgba(15,23,42,0.2)] hover:shadow-[0_28px_60px_-30px_rgba(15,23,42,0.22)] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between dark:bg-slate-950/70 dark:border-gray-700">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2 dark:text-slate-400">Espace Commercial</h4>
                            <p class="text-slate-700 text-sm mb-4 dark:text-slate-300">Gestion du CRM client et suivi des nouvelles commandes.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('orders.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-teal-400 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                                Gérer les commandes
                            </a>
                            <a href="{{ route('customers.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-purple-600 border border-purple-200 font-medium rounded-lg text-sm hover:bg-purple-50 transition-colors dark:bg-slate-900 dark:text-white dark:border-gray-700 dark:hover:bg-slate-800">
                                Annuaire d'appels
                            </a>
                        </div>
                    </div>
                @endif

                {{-- BLOC 3 : Visible par le Gestionnaire et le Directeur --}}
                @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-slate-50/80 rounded-3xl border border-slate-200 p-6 shadow-[0_25px_50px_-25px_rgba(15,23,42,0.2)] hover:shadow-[0_28px_60px_-30px_rgba(15,23,42,0.22)] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between dark:bg-slate-950/70 dark:border-gray-700">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2 dark:text-slate-400">Espace Logistique</h4>
                            <p class="text-slate-700 text-sm mb-4 dark:text-slate-300">Mise à jour du catalogue et alertes de stock critique.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('products.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-500 to-teal-400 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                                Gérer l'inventaire
                            </a>
                            <a href="{{ route('products.alerts') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-teal-600 border border-teal-200 font-medium rounded-lg text-sm hover:bg-teal-50 transition-colors dark:bg-slate-900 dark:text-white dark:border-gray-700 dark:hover:bg-slate-800">
                                Alertes Stock Critique
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>