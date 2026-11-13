<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Détails de la commande') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-200 dark:hover:bg-slate-800">
                    ← Retour à la liste
                </a>
                @if(in_array(auth()->user()->role, ['super_admin', 'agent']))
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('orders.label', $order) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-200 dark:hover:bg-slate-800">
                            <i data-lucide="printer" class="h-4 w-4"></i> Bon de livraison
                        </a>
                        <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 border border-teal-500 rounded-lg text-sm font-medium text-white hover:bg-teal-600">
                            <i data-lucide="download" class="h-4 w-4"></i> Télécharger la Facture
                        </a>
                    </div>
                @endif
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden dark:bg-slate-900 dark:border dark:border-slate-800">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-gray-100">Informations de la commande</h3>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">N° commande : <span class="font-medium text-slate-900 dark:text-gray-100">#{{ $order->id }}</span></p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Date : <span class="font-medium text-slate-900 dark:text-gray-100">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                            Client :
                            <a href="{{ route('customers.show', $order->customer) }}" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">{{ $order->customer->name }}</a>
                            @if($order->customer->delivery_rate < 50)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-600 rounded-full text-xs font-medium dark:bg-rose-900/25 dark:text-rose-200">Risque élevé ({{ $order->customer->delivery_rate }}%)</span>
                            @elseif($order->customer->delivery_rate >= 80)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-teal-50 text-teal-600 rounded-full text-xs font-medium dark:bg-teal-900/25 dark:text-teal-200">Client sérieux ({{ $order->customer->delivery_rate }}%)</span>
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Agent responsable : <span class="font-medium text-slate-900 dark:text-gray-100">{{ $order->user->name }}</span></p>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            @if($order->customer->whatsapp_number)
                                <a href="https://wa.me/{{ $order->customer->whatsapp_number }}?text={{ urlencode('Bonjour ' . $order->customer->name . ', je vous contacte au sujet de votre commande #' . $order->id . '. Vous pouvez suivre son statut ici : ' . $order->tracking_url) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i data-lucide="message-circle" class="h-4 w-4"></i> Contacter sur WhatsApp
                                </a>
                            @endif
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $order->tracking_url }}'); this.dataset.label = this.innerHTML; this.innerHTML = '<span>Lien copié !</span>'; setTimeout(() => this.innerHTML = this.dataset.label, 2000);" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors dark:bg-slate-950 dark:border-slate-700 dark:text-gray-200 dark:hover:bg-slate-800">
                                <i data-lucide="link" class="h-4 w-4"></i> Copier le lien de suivi
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <h4 class="text-base font-semibold text-slate-800 mb-4 dark:text-gray-100">Produits de la commande</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-sm text-slate-600 uppercase tracking-wide dark:bg-slate-800 dark:text-slate-300">
                                        <th class="p-3 border-b border-slate-200 dark:border-slate-700">Produit</th>
                                        <th class="p-3 border-b border-slate-200 dark:border-slate-700">Quantité</th>
                                        <th class="p-3 border-b border-slate-200 dark:border-slate-700">Prix unitaire</th>
                                        <th class="p-3 border-b border-slate-200 dark:border-slate-700">Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->products as $product)
                                        <tr class="border-b hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">
                                            <td class="p-3 text-sm text-slate-800 dark:text-gray-100">{{ $product->name }}</td>
                                            <td class="p-3 text-sm text-slate-700 dark:text-slate-300">{{ $product->pivot->quantity }}</td>
                                            <td class="p-3 text-sm text-slate-700 dark:text-slate-300">{{ number_format($product->pivot->unit_price, 2) }} DH</td>
                                            <td class="p-3 text-sm font-semibold text-slate-900 dark:text-gray-100">{{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2) }} DH</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white shadow-sm rounded-2xl overflow-hidden dark:bg-slate-900 dark:border dark:border-slate-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 dark:text-gray-100">Gérer le statut</h3>

                            @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('agent'))
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Statut actuel :</label>
                                    <select id="status" name="status" class="mt-2 block w-full rounded-lg border-slate-300 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">
                                        @foreach(['pending' => 'En attente', 'confirmed' => 'Confirmée', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'returned' => 'Retournée', 'canceled' => 'Annulée'] as $value => $label)
                                            @if($value === $order->status || in_array($value, \App\Services\OrderStatusService::TRANSITIONS[$order->status] ?? [], true))
                                            <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    <button type="submit" class="mt-4 w-full inline-flex justify-center bg-gradient-to-r from-indigo-500 to-violet-600 hover:shadow-lg text-white font-semibold py-2 px-4 rounded-lg shadow-md shadow-indigo-200/50 dark:shadow-none transition-all border-0">
                                        Mettre à jour le statut
                                    </button>
                                </form>
                            @else
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                    <p class="text-sm text-slate-700 dark:text-slate-300">Statut actuel de la commande : <span class="font-semibold text-slate-900 dark:text-gray-100">{{ $order->status }}</span></p>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="mt-4 rounded-lg border border-teal-200 bg-teal-50 p-4 text-sm text-teal-700 dark:border-teal-700 dark:bg-teal-900/25 dark:text-teal-100">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl overflow-hidden dark:bg-slate-900 dark:border dark:border-slate-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 dark:text-gray-100">Récapitulatif</h3>
                            <div class="space-y-2 text-sm text-slate-700 dark:text-slate-300">
                                <p>Frais de livraison : <span class="font-medium text-slate-900 dark:text-gray-100">{{ number_format($order->delivery_fee, 2) }} DH</span></p>
                                <p>Montant total : <span class="font-semibold text-slate-900 dark:text-gray-100">{{ number_format($order->total_amount, 2) }} DH</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-2xl overflow-hidden dark:bg-slate-900 dark:border dark:border-slate-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 dark:text-gray-100">Historique de la commande</h3>

                            @if($order->activityLogs->isEmpty())
                                <p class="text-sm text-slate-600 dark:text-slate-400">Aucun changement de statut pour le moment.</p>
                            @else
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-5 w-px bg-slate-200 dark:bg-slate-800"></div>
                                    <div class="space-y-6 pl-12">
                                        @foreach($order->activityLogs as $log)
                                            <div class="relative">
                                                <span class="absolute left-0 top-1.5 flex h-3.5 w-3.5">
                                                    <span class="absolute inline-flex h-full w-full rounded-full bg-indigo-600 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-white border border-indigo-600 dark:bg-slate-800"></span>
                                                </span>
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                                                    <div class="flex items-center justify-between gap-4 text-sm text-slate-500 dark:text-slate-400">
                                                        <span>{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                                        <span class="rounded-full bg-indigo-100 text-indigo-700 px-2 py-1 text-xs font-semibold uppercase tracking-wide dark:bg-indigo-900 dark:text-indigo-200">{{ $log->user?->name ?? 'Utilisateur' }}</span>
                                                    </div>
                                                    <div class="mt-3 text-sm text-slate-700 dark:text-slate-300">
                                                        <p class="font-medium text-slate-900 dark:text-gray-100">{{ ucfirst($log->old_status) }} → {{ ucfirst($log->new_status) }}</p>
                                                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Statut modifié par {{ $log->user?->name ?? 'Utilisateur inconnu' }}.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
