<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fiche Client') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-slate-800">
                    ← Retour à l'annuaire
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Colonne gauche : Profil du client --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 dark:bg-slate-900 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center dark:bg-slate-800">
                                <svg class="w-8 h-8 text-blue-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $customer->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Client depuis le {{ $customer->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                @if($customer->email)
                                    <p class="text-gray-900">{{ $customer->email }}</p>
                                @else
                                    <p class="text-gray-400 italic dark:text-slate-400">Non renseigné</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Téléphone</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $customer->phone ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Adresse</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $customer->address ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total des commandes</p>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-300">{{ $customer->orders_count ?? $customer->orders->count() }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total dépensé</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-teal-300">{{ number_format($customer->orders->sum('total_amount'), 2) }} DH</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Colonne droite : Historique des commandes --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 dark:bg-slate-900 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 dark:text-gray-100">Historique des commandes</h3>

                        @if($customer->orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 border-b-2 border-gray-200 dark:bg-slate-800 dark:border-gray-700">
                                            <th class="p-3 font-bold text-gray-600 dark:text-slate-300">N° Commande</th>
                                            <th class="p-3 font-bold text-gray-600 dark:text-slate-300">Date</th>
                                            <th class="p-3 font-bold text-gray-600 dark:text-slate-300">Montant</th>
                                            <th class="p-3 font-bold text-gray-600 dark:text-slate-300">Statut</th>
                                            <th class="p-3 font-bold text-gray-600 dark:text-slate-300 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->orders as $order)
                                            <tr class="border-b hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-slate-800">
                                                <td class="p-3 font-semibold text-gray-900 dark:text-gray-100">#{{ $order->id }}</td>
                                                <td class="p-3 text-sm text-gray-600 dark:text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="p-3 font-bold text-indigo-600 dark:text-indigo-300">{{ number_format($order->total_amount, 2) }} DH</td>
                                                <td class="p-3">
                                                    @switch($order->status)
                                                        @case('pending')
                                                            <span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded-full text-xs font-medium dark:bg-yellow-900/30 dark:text-yellow-200">En attente</span>
                                                            @break
                                                        @case('confirmed')
                                                            <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium dark:bg-blue-900/30 dark:text-blue-200">Confirmée</span>
                                                            @break
                                                        @case('shipped')
                                                            <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-medium dark:bg-indigo-900/30 dark:text-indigo-200">Expédiée</span>
                                                            @break
                                                        @case('delivered')
                                                            <span class="px-2 py-1 bg-teal-50 text-teal-600 rounded-full text-xs font-medium dark:bg-teal-900/30 dark:text-teal-200">Livrée</span>
                                                            @break
                                                        @case('canceled')
                                                            <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-medium dark:bg-purple-900/30 dark:text-purple-200">Annulée</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td class="p-3 text-center">
                                                    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 font-medium">
                                                        Voir
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>Aucune commande pour ce client.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
