<!-- resources/views/orders/partials/table.blade.php -->
<div class="overflow-x-auto rounded-[1.5rem] bg-white/90 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 shadow-sm">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-gray-700 dark:bg-slate-900">
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300">N° Commande</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300">Client</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300">Saisie par</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300">Frais Liv.</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300">Montant Total</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300">Statut</th>
                <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-300 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b border-slate-200 hover:bg-slate-50 dark:border-gray-700 dark:hover:bg-slate-800 transition-colors">
                    <td class="p-3 font-semibold text-slate-900 dark:text-gray-200">
                        <a href="{{ route('orders.show', $order) }}" class="text-purple-600 hover:underline transition-colors dark:text-purple-300">#{{ $order->id }}</a>
                    </td>
                    <td class="p-3 text-slate-700 dark:text-gray-200">
                        <a href="{{ route('customers.show', $order->customer) }}" class="text-purple-600 hover:underline transition-colors dark:text-purple-300">{{ $order->customer->name }}</a>
                    </td>
                    <td class="p-3 text-sm text-slate-500 dark:text-gray-200">{{ $order->user->name }}</td>
                    <td class="p-3 text-slate-700 dark:text-gray-200">{{ number_format($order->delivery_fee, 2) }} DH</td>
                    <td class="p-3 font-bold text-slate-900 dark:text-gray-200">{{ number_format($order->total_amount, 2) }} DH</td>
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
                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 dark:text-purple-300 dark:hover:text-purple-200 font-medium">
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