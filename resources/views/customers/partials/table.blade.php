<tbody>
    @forelse($customers as $customer)
        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors dark:border-slate-800 dark:hover:bg-slate-800">
            <td class="p-3 font-semibold text-slate-900 dark:text-gray-100">
                <a href="{{ route('customers.show', $customer) }}" class="text-indigo-600 hover:underline transition-colors">{{ $customer->name }}</a>
            </td>
            <td class="p-3 text-sm text-slate-700 dark:text-slate-200">{{ $customer->email }}</td>
            <td class="p-3 text-sm text-slate-500 dark:text-slate-400">{{ $customer->phone ?? 'N/A' }}</td>
            <td class="p-3 text-center">
                <span class="inline-flex items-center justify-center px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-sm font-medium dark:bg-indigo-900/20 dark:text-indigo-200">{{ $customer->orders_count }}</span>
            </td>
            <td class="p-3 text-center">
                @if ($customer->delivery_rate < 50)
                    <span class="text-red-600 font-medium">{{ $customer->delivery_rate }}%</span>
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-red-50 text-red-600 rounded-full text-xs font-medium dark:bg-red-900/25 dark:text-red-200">Risque élevé</span>
                @elseif ($customer->delivery_rate < 80)
                    <span class="text-orange-500 font-medium">{{ $customer->delivery_rate }}%</span>
                @else
                    <span class="text-teal-600 font-medium">{{ $customer->delivery_rate }}%</span>
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-teal-50 text-teal-600 rounded-full text-xs font-medium dark:bg-teal-900/25 dark:text-teal-200">Client sérieux</span>
                @endif
            </td>
            <td class="p-3 text-right font-bold text-slate-900 dark:text-gray-100">{{ number_format($customer->orders->sum('total_amount'), 2, ',', ' ') }} DH</td>
            <td class="p-3 text-center">
                <div class="inline-flex items-center gap-3">
                    @if($customer->whatsapp_number)
                        <a href="https://wa.me/{{ $customer->whatsapp_number }}?text={{ urlencode('Bonjour ' . $customer->name . ', je vous contacte au sujet de votre compte client.') }}" target="_blank" rel="noopener" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300" title="Contacter sur WhatsApp">
                            <i data-lucide="message-circle" class="h-4 w-4"></i>
                        </a>
                    @endif
                    <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 font-medium">Détails</a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="p-10">
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center dark:border-slate-700 dark:bg-slate-900">
                    <svg class="mx-auto h-14 w-14 text-slate-400 dark:text-slate-500" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 16H44V48H20V16Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M24 24H40" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <path d="M24 32H40" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <path d="M24 40H33" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <p class="mt-5 text-lg font-semibold text-slate-900 dark:text-gray-100">Aucun client trouvé</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Vérifiez les filtres ou ajoutez un client pour commencer.</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
