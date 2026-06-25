<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b-2 border-gray-200">
                <th class="p-3 font-bold text-gray-600">N° Commande</th>
                <th class="p-3 font-bold text-gray-600">Client</th>
                <th class="p-3 font-bold text-gray-600">Saisie par</th>
                <th class="p-3 font-bold text-gray-600">Frais Liv.</th>
                <th class="p-3 font-bold text-gray-600">Montant Total</th>
                <th class="p-3 font-bold text-gray-600">Statut</th>
                <th class="p-3 font-bold text-gray-600 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-semibold">#{{ $order->id }}</td>
                    <td class="p-3">{{ $order->customer->name }}</td>
                    <td class="p-3 text-sm text-gray-600">{{ $order->user->name }}</td>
                    <td class="p-3">{{ number_format($order->delivery_fee, 2) }} DH</td>
                    <td class="p-3 font-bold text-indigo-600">{{ number_format($order->total_amount, 2) }} DH</td>
                    <td class="p-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">En attente</span>
                                @break
                            @case('confirmed')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">Confirmée</span>
                                @break
                            @case('shipped')
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-bold">Expédiée</span>
                                @break
                            @case('delivered')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Livrée </span>
                                @break
                            @case('canceled')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Annulée</span>
                                @break
                        @endswitch
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-xs bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition">
                            Détails / Modifier
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
