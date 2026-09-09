<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Alertes de Stock') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 dark:text-gray-100 flex items-center gap-2">
                        <i data-lucide="triangle-alert" class="h-5 w-5 text-rose-500"></i>
                        Produits en alerte de stock critique
                    </h3>

                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 dark:bg-slate-950 dark:border-slate-800">
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-400">Produit</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-400">Référence</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-400">Prix de vente</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-400">Stock actuel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors dark:border-slate-800 dark:hover:bg-slate-800">
                                        <td class="p-3 font-medium text-slate-900 dark:text-gray-100">{{ $product->name }}</td>
                                        <td class="p-3 text-sm text-slate-500 dark:text-slate-400">{{ $product->sku ?? 'N/A' }}</td>
                                        <td class="p-3 text-sm text-slate-500 dark:text-slate-400">{{ number_format($product->selling_price, 2, ',', ' ') }} DH</td>
                                        <td class="p-3 text-sm font-semibold text-rose-600 dark:text-rose-400">{{ $product->stock_quantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-slate-500 dark:text-slate-400 font-medium">
                                            Aucun produit en alerte de stock critique.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
