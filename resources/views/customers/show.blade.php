<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Fiche Client') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-200 dark:hover:bg-slate-800">
                    ← Retour à l'annuaire
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Colonne gauche : Profil du client --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center dark:bg-indigo-500/10">
                                <i data-lucide="user" class="w-8 h-8 text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-gray-100">{{ $customer->name }}</h3>
                                <p class="text-sm text-slate-500 dark:text-gray-400">Client depuis le {{ $customer->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-gray-400">Email</p>
                                @if($customer->email)
                                    <p class="text-slate-900 dark:text-gray-100">{{ $customer->email }}</p>
                                @else
                                    <p class="text-slate-400 italic dark:text-slate-400">Non renseigné</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-gray-400">Téléphone</p>
                                <p class="text-slate-900 dark:text-gray-100">{{ $customer->phone ?? 'Non renseigné' }}</p>
                                @if($customer->whatsapp_number)
                                    <a href="https://wa.me/{{ $customer->whatsapp_number }}?text={{ urlencode('Bonjour ' . $customer->name . ', je vous contacte au sujet de votre compte client.') }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                                        <i data-lucide="message-circle" class="h-4 w-4"></i> Contacter sur WhatsApp
                                    </a>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-gray-400">Adresse</p>
                                <p class="text-slate-900 dark:text-gray-100">{{ $customer->address ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-gray-400">Total des commandes</p>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $customer->orders_count ?? $customer->orders->count() }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-gray-400">Total dépensé</p>
                                <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">{{ number_format($customer->orders->sum('total_amount'), 2) }} DH</p>
                            </div>
                            @if($customer->orders->count() > 0)
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-gray-400">Taux de livraison</p>
                                    @if($customer->delivery_rate < 50)
                                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $customer->delivery_rate }}%</p>
                                        <span class="mt-1 inline-flex items-center px-2 py-0.5 bg-red-50 text-red-600 rounded-full text-xs font-medium dark:bg-red-900/25 dark:text-red-200">Risque élevé</span>
                                    @elseif($customer->delivery_rate < 80)
                                        <p class="text-2xl font-bold text-orange-500">{{ $customer->delivery_rate }}%</p>
                                    @else
                                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">{{ $customer->delivery_rate }}%</p>
                                        <span class="mt-1 inline-flex items-center px-2 py-0.5 bg-teal-50 text-teal-600 rounded-full text-xs font-medium dark:bg-teal-900/25 dark:text-teal-200">Client sérieux</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Colonne droite : Historique des commandes --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 dark:bg-slate-900 dark:border-slate-800">
                        <h3 class="text-lg font-semibold text-slate-900 mb-6 dark:text-gray-100">Historique des commandes</h3>

                        @if($customer->orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-100 border-b-2 border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                                            <th class="p-3 font-bold text-slate-600 dark:text-slate-300">N° Commande</th>
                                            <th class="p-3 font-bold text-slate-600 dark:text-slate-300">Date</th>
                                            <th class="p-3 font-bold text-slate-600 dark:text-slate-300">Montant</th>
                                            <th class="p-3 font-bold text-slate-600 dark:text-slate-300">Statut</th>
                                            <th class="p-3 font-bold text-slate-600 dark:text-slate-300 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->orders as $order)
                                            <tr class="border-b hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">
                                                <td class="p-3 font-semibold text-slate-900 dark:text-gray-100">#{{ $order->id }}</td>
                                                <td class="p-3 text-sm text-slate-600 dark:text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="p-3 font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($order->total_amount, 2) }} DH</td>
                                                <td class="p-3">
                                                    @switch($order->status)
                                                        @case('pending')
                                                            <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-medium dark:bg-amber-900/30 dark:text-amber-200">En attente</span>
                                                            @break
                                                        @case('confirmed')
                                                            <span class="px-2 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-medium dark:bg-violet-900/30 dark:text-violet-200">Confirmée</span>
                                                            @break
                                                        @case('shipped')
                                                            <span class="px-2 py-1 bg-cyan-50 text-cyan-700 rounded-full text-xs font-medium dark:bg-cyan-900/30 dark:text-cyan-200">Expédiée</span>
                                                            @break
                                                        @case('delivered')
                                                            <span class="px-2 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-medium dark:bg-teal-900/30 dark:text-teal-200">Livrée</span>
                                                            @break
                                                        @case('canceled')
                                                            <span class="px-2 py-1 bg-fuchsia-50 text-fuchsia-700 rounded-full text-xs font-medium dark:bg-fuchsia-900/30 dark:text-fuchsia-200">Annulée</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td class="p-3 text-center">
                                                    <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                                        Voir
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-500 dark:text-gray-400">
                                <p>Aucune commande pour ce client.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
