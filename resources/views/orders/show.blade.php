<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la commande') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Retour à la liste
                </a>
                <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700">
                    Télécharger la Facture
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Informations de la commande</h3>
                        <p class="mt-3 text-sm text-gray-600">N° commande : <span class="font-medium">#{{ $order->id }}</span></p>
                        <p class="mt-1 text-sm text-gray-600">Date : <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
                        <p class="mt-1 text-sm text-gray-600">Client : <span class="font-medium">{{ $order->customer->name }}</span></p>
                        <p class="mt-1 text-sm text-gray-600">Agent responsable : <span class="font-medium">{{ $order->user->name }}</span></p>
                    </div>

                    <div class="p-6">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Produits de la commande</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600 uppercase tracking-wide">
                                        <th class="p-3 border-b border-gray-200">Produit</th>
                                        <th class="p-3 border-b border-gray-200">Quantité</th>
                                        <th class="p-3 border-b border-gray-200">Prix unitaire</th>
                                        <th class="p-3 border-b border-gray-200">Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->products as $product)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-3 text-sm text-gray-800">{{ $product->name }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ $product->pivot->quantity }}</td>
                                            <td class="p-3 text-sm text-gray-700">{{ number_format($product->pivot->unit_price, 2) }} DH</td>
                                            <td class="p-3 text-sm font-semibold text-gray-900">{{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2) }} DH</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Gérer le statut</h3>

                            @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('agent'))
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <label for="status" class="block text-sm font-medium text-gray-700">Statut actuel :</label>
                                    <select id="status" name="status" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach(['pending' => 'En attente', 'confirmed' => 'Confirmée', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'canceled' => 'Annulée'] as $value => $label)
                                            <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="mt-4 w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Mettre à jour le statut
                                    </button>
                                </form>
                            @else
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm text-gray-700">Statut actuel de la commande : <span class="font-semibold">{{ $order->status }}</span></p>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="mt-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Récapitulatif</h3>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>Frais de livraison : <span class="font-medium">{{ number_format($order->delivery_fee, 2) }} DH</span></p>
                                <p>Montant total : <span class="font-semibold">{{ number_format($order->total_amount, 2) }} DH</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Historique de la commande</h3>

                            @if($order->activityLogs->isEmpty())
                                <p class="text-sm text-gray-600">Aucun changement de statut pour le moment.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($order->activityLogs as $log)
                                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                            <p class="text-sm text-gray-800">{{ $log->user?->name ?? 'Utilisateur inconnu' }} a modifié le statut de <span class="font-semibold">{{ $log->old_status }}</span> à <span class="font-semibold">{{ $log->new_status }}</span>.</p>
                                            <p class="mt-1 text-xs text-gray-500">Le {{ $log->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
