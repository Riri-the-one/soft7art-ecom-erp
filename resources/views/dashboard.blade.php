<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Tableau de bord ERP') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-violet-600 rounded-2xl shadow-lg p-8 mb-8 text-white w-full">
                <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -right-24 bottom-0 h-40 w-40 rounded-full bg-white/10"></div>
                <h3 class="relative text-3xl font-bold mb-2">Bonjour, {{ auth()->user()->name }}</h3>
                <p class="relative text-indigo-50 text-lg max-w-2xl">Votre ERP est prêt à piloter les ventes, la logistique et le suivi client avec une expérience moderne et fluide.</p>
            </div>

            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'agent')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <a href="{{ route('customers.index') }}" class="group block w-full bg-white rounded-2xl shadow-sm hover:shadow-md transition-all p-6 border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-slate-400">Total Clients</p>
                            <h4 class="text-3xl font-bold text-slate-900 dark:text-gray-100">{{ $totalClients ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:bg-indigo-100 transition-colors dark:bg-indigo-500/10 dark:text-indigo-400 dark:group-hover:bg-indigo-500/20">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-indigo-600 dark:text-indigo-400 inline-flex items-center gap-1">voir plus <i data-lucide="arrow-right" class="h-3 w-3"></i></p>
                </a>

                <a href="{{ route('orders.index') }}" class="group block w-full bg-white rounded-2xl shadow-sm hover:shadow-md transition-all p-6 border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-slate-400">Commandes du jour</p>
                            <h4 class="text-3xl font-bold text-slate-900 dark:text-gray-100">{{ $ordersToday ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-teal-50 rounded-xl text-teal-600 group-hover:bg-teal-100 transition-colors dark:bg-teal-500/10 dark:text-teal-400 dark:group-hover:bg-teal-500/20">
                            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        </div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-teal-600 dark:text-teal-400 inline-flex items-center gap-1">voir plus <i data-lucide="arrow-right" class="h-3 w-3"></i></p>
                </a>

                <a href="{{ route('products.index', ['stock' => 'critical']) }}" class="group block w-full bg-white rounded-2xl shadow-sm hover:shadow-md transition-all p-6 border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-slate-400">Stock critique</p>
                            <h4 class="text-3xl font-bold text-slate-900 dark:text-gray-100">{{ $criticalStock ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-xl text-rose-600 group-hover:bg-rose-100 transition-colors dark:bg-rose-500/10 dark:text-rose-400 dark:group-hover:bg-rose-500/20">
                            <i data-lucide="triangle-alert" class="w-6 h-6"></i>
                        </div>
                    </div>
                    <p class="mt-3 text-xs font-medium text-rose-600 dark:text-rose-400 inline-flex items-center gap-1">voir plus <i data-lucide="arrow-right" class="h-3 w-3"></i></p>
                </a>

                <div class="w-full bg-white rounded-2xl shadow-sm hover:shadow-md transition-all p-6 border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 dark:text-slate-400">CA du mois</p>
                            <h4 class="text-3xl font-bold text-slate-900 dark:text-gray-100">{{ number_format($monthlyRevenue ?? 0, 0, ',', ' ') }} DH</h4>
                        </div>
                        <div class="p-3 bg-violet-50 rounded-xl text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                            <i data-lucide="banknote" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>

            </div>
            @endif

            {{-- Super Admin Specific Widgets --}}
            @if(auth()->user()->role === 'super_admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Monthly Goal Widget --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                <i data-lucide="target" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Objectif Mensuel (50 000 DH)</h4>
                        </div>
                        <div class="mb-2">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500 dark:text-slate-400">Progression</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ number_format($monthlyGoal, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 dark:bg-slate-800">
                                <div class="bg-gradient-to-r from-indigo-500 to-violet-600 h-3 rounded-full" style="width: {{ $monthlyGoal }}%"></div>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">{{ number_format($monthlyRevenue, 0) }} DH réalisés ce mois</p>
                    </div>

                    {{-- Top 5 Products Widget --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                                <i data-lucide="package" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Top 5 Produits</h4>
                        </div>
                        <div class="space-y-3">
                            @forelse($topProducts as $index => $product)
                                <div class="flex items-center justify-between gap-3 py-2">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="w-6 h-6 shrink-0 flex items-center justify-center bg-violet-100 text-violet-600 rounded-full text-xs font-bold dark:bg-violet-500/20 dark:text-violet-300">{{ $index + 1 }}</span>
                                        <span class="truncate text-slate-900 font-medium dark:text-gray-100">{{ $product->name }}</span>
                                    </div>
                                    <span class="shrink-0 text-sm font-semibold text-violet-600 dark:text-violet-400">{{ $product->units_sold }} vendu{{ $product->units_sold > 1 ? 's' : '' }}</span>
                                </div>
                            @empty
                                <p class="py-6 text-center text-sm text-slate-400 dark:text-slate-500">Aucune vente enregistrée pour le moment.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent Activity Widget --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                                <i data-lucide="activity" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Dernières Activités</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach($recentActivity as $activity)
                                <a href="{{ route('orders.show', $activity) }}" class="block rounded-xl py-2 border-b border-slate-100 last:border-0 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-slate-900 font-medium dark:text-gray-100">{{ $activity->customer->name }}</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">Commande #{{ $activity->id }}</p>
                                        </div>
                                        <span class="text-indigo-600 dark:text-indigo-400 font-bold text-sm">{{ number_format($activity->total_amount, 0) }} DH</span>
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-500">{{ $activity->created_at->format('d/m/Y H:i') }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Agent Leaderboard Widget --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 mb-8 dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                            <i data-lucide="trophy" class="h-4 w-4"></i>
                        </span>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Classement des Agents</h4>
                        <span class="text-xs text-slate-400 dark:text-slate-500">(commandes confirmées + livrées)</span>
                    </div>
                    @if(($agentLeaderboard ?? collect())->isEmpty())
                        <p class="text-sm text-slate-500 dark:text-slate-400">Aucun agent avec des commandes pour le moment.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($agentLeaderboard as $agent)
                                @php
                                    $medalBg = match($loop->index) {
                                        0 => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
                                        1 => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200',
                                        2 => 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-300',
                                        default => 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400',
                                    };
                                @endphp
                                <div class="flex items-center justify-between rounded-xl p-3 {{ $loop->first ? 'bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-500/10 dark:to-violet-500/10' : 'hover:bg-slate-50 dark:hover:bg-slate-800' }} transition">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold {{ $medalBg }}">
                                            {{ $loop->index + 1 }}
                                        </span>
                                        <span class="text-slate-900 font-medium dark:text-gray-100">{{ $agent->name }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-300">{{ $agent->score }} commande{{ $agent->score > 1 ? 's' : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Agent Specific Widgets --}}
            @if(auth()->user()->role === 'agent')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 auto-rows-fr">
                    {{-- Success Rate Widget --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                <i data-lucide="target" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Taux de Succès</h4>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl text-indigo-600 dark:text-indigo-400 font-bold">{{ $agentSuccessRate }}%</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Commandes livrées ou confirmées</p>
                        </div>
                    </div>

                    {{-- Recent Customers Widget --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 md:col-span-2 h-full flex flex-col dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                                <i data-lucide="user-plus" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Derniers Clients Acquis</h4>
                        </div>
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
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                                <i data-lucide="warehouse" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Valeur Totale du Stock</h4>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl text-teal-600 dark:text-teal-400 font-bold">{{ number_format($inventoryValue, 0) }} DH</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Valeur d'achat totale</p>
                        </div>
                    </div>

                    {{-- Critical Stock Widget --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 h-full flex flex-col dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                                <i data-lucide="triangle-alert" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-gray-100">Ruptures Imminentes (Stock &lt; 10)</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold pb-2">Produit</th>
                                        <th class="text-left text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold pb-2">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalStock as $product)
                                        <tr class="border-b border-slate-100 dark:border-slate-800 last:border-0">
                                            <td class="py-2 text-slate-900 dark:text-gray-100">{{ $product->name }}</td>
                                            <td class="py-2 text-rose-600 dark:text-rose-400 font-medium">{{ $product->stock_quantity }}</td>
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
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between dark:bg-slate-900 dark:border-slate-800">
                        <div>
                            <span class="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                <i data-lucide="briefcase" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2 dark:text-slate-400">Espace Direction</h4>
                            <p class="text-slate-700 text-sm mb-4 dark:text-slate-300">Vue globale sur le chiffre d'affaires et la gestion des employés.</p>
                        </div>
                        <a href="{{ route('statistics.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                            Voir les statistiques
                        </a>
                    </div>
                @endif

                {{-- BLOC 2 : Visible par l'Agent et le Directeur --}}
                @if(Auth::user()->hasRole('agent') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between dark:bg-slate-900 dark:border-slate-800">
                        <div>
                            <span class="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                                <i data-lucide="phone" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2 dark:text-slate-400">Espace Commercial</h4>
                            <p class="text-slate-700 text-sm mb-4 dark:text-slate-300">Gestion du CRM client et suivi des nouvelles commandes.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('orders.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                                Gérer les commandes
                            </a>
                            <a href="{{ route('customers.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-indigo-600 border border-indigo-200 font-medium rounded-lg text-sm hover:bg-indigo-50 transition-colors dark:bg-slate-950 dark:text-indigo-300 dark:border-slate-700 dark:hover:bg-slate-800">
                                Annuaire d'appels
                            </a>
                        </div>
                    </div>
                @endif

                {{-- BLOC 3 : Visible par le Gestionnaire et le Directeur --}}
                @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between dark:bg-slate-900 dark:border-slate-800">
                        <div>
                            <span class="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                                <i data-lucide="warehouse" class="h-4 w-4"></i>
                            </span>
                            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2 dark:text-slate-400">Espace Logistique</h4>
                            <p class="text-slate-700 text-sm mb-4 dark:text-slate-300">Mise à jour du catalogue et alertes de stock critique.</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('products.index') }}" class="inline-flex justify-center w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all">
                                Gérer l'inventaire
                            </a>
                            <a href="{{ route('products.alerts') }}" class="inline-flex justify-center w-full px-4 py-2 bg-white text-teal-600 border border-teal-200 font-medium rounded-lg text-sm hover:bg-teal-50 transition-colors dark:bg-slate-950 dark:text-teal-300 dark:border-slate-700 dark:hover:bg-slate-800">
                                Alertes Stock Critique
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
