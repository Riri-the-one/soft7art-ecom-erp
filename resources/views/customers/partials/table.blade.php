<tbody>
    @forelse($customers as $customer)
        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors dark:border-gray-700 dark:hover:bg-slate-800">
            <td class="p-3 font-semibold text-slate-900 dark:text-gray-100">
                <a href="{{ route('customers.show', $customer) }}" class="text-purple-600 hover:underline transition-colors">{{ $customer->name }}</a>
            </td>
            <td class="p-3 text-sm text-slate-700 dark:text-slate-200">{{ $customer->email }}</td>
            <td class="p-3 text-sm text-slate-500 dark:text-slate-400">{{ $customer->phone ?? 'N/A' }}</td>
            <td class="p-3 text-center">
                <span class="inline-flex items-center justify-center px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-sm font-medium">{{ $customer->orders_count }}</span>
            </td>
            <td class="p-3 text-center">
                @if ($customer->delivery_rate < 50)
                    <span class="text-red-600 font-medium">{{ $customer->delivery_rate }}%</span>
                @elseif ($customer->delivery_rate < 80)
                    <span class="text-orange-500 font-medium">{{ $customer->delivery_rate }}%</span>
                @else
                    <span class="text-teal-600 font-medium">{{ $customer->delivery_rate }}%</span>
                @endif
            </td>
            <td class="p-3 text-right font-bold text-slate-900 dark:text-gray-100">{{ number_format($customer->orders->sum('total_amount'), 2, ',', ' ') }} DH</td>
            <td class="p-3 text-center">
                <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 font-medium">Détails</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="p-6 text-center text-slate-500 dark:text-slate-400 font-medium">Aucun client trouvé dans la base de donnees.</td>
        </tr>
    @endforelse
</tbody>
