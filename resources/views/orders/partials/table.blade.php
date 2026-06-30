<div class="overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">N° Commande</th>
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Client</th>
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Saisie par</th>
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Frais Liv.</th>
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Montant Total</th>
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Statut</th>
                <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="p-3 font-semibold text-gray-800">#{{ $order->id }}</td>
                    <td class="p-3 text-gray-700">{{ $order->customer->name }}</td>
                    <td class="p-3 text-sm text-gray-400">{{ $order->user->name }}</td>
                    <td class="p-3 text-gray-700">{{ number_format($order->delivery_fee, 2) }} DH</td>
                    <td class="p-3 font-bold text-gray-800">{{ number_format($order->total_amount, 2) }} DH</td>
                    <td class="p-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded-full text-xs font-medium">En attente</span>
                                @break
                            @case('confirmed')
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium">Confirmée</span>
                                @break
                            @case('shipped')
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-medium">Expédiée</span>
                                @break
                            @case('delivered')
                                <span class="px-2 py-1 bg-teal-50 text-teal-600 rounded-full text-xs font-medium">Livrée</span>
                                @break
                            @case('canceled')
                                <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-medium">Annulée</span>
                                @break
                        @endswitch
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 font-medium">
                            Détails
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
