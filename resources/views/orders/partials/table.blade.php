<div class="overflow-x-auto rounded-[1.5rem] bg-white/90 border border-slate-200 shadow-sm dark:bg-gray-900 dark:border-gray-700">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-gray-700 dark:bg-slate-900">
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">N° Commande</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Client</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Saisie par</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Frais Liv.</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Montant Total</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Statut</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                    <td class="p-3 font-semibold text-slate-900">
                        <a href="{{ route('orders.show', $order) }}" class="text-purple-600 hover:underline transition-colors">#{{ $order->id }}</a>
                    </td>
                    <td class="p-3 text-slate-700">
                        <a href="{{ route('customers.show', $order->customer) }}" class="text-purple-600 hover:underline transition-colors">{{ $order->customer->name }}</a>
                    </td>
                    <td class="p-3 text-sm text-slate-500">{{ $order->user->name }}</td>
                    <td class="p-3 text-slate-700">{{ number_format($order->delivery_fee, 2) }} DH</td>
                    <td class="p-3 font-bold text-slate-900">{{ number_format($order->total_amount, 2) }} DH</td>
                    <td class="p-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-medium">En attente</span>
                                @break
                            @case('confirmed')
                                <span class="px-2 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-medium">Confirmée</span>
                                @break
                            @case('shipped')
                                <span class="px-2 py-1 bg-cyan-50 text-cyan-700 rounded-full text-xs font-medium">Expédiée</span>
                                @break
                            @case('delivered')
                                <span class="px-2 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-medium">Livrée</span>
                                @break
                            @case('canceled')
                                <span class="px-2 py-1 bg-fuchsia-50 text-fuchsia-700 rounded-full text-xs font-medium">Annulée</span>
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
