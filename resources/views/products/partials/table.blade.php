
    @forelse($products as $product)
        @php
            $stockPercent = min(100, max(0, $product->stock_quantity));
            $stockColor = $product->isStockCritical() ? 'bg-rose-500' : ($product->stock_quantity < 25 ? 'bg-amber-500' : 'bg-emerald-500');
        @endphp
        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors dark:border-slate-800 dark:hover:bg-slate-800">
            <td class="p-3 text-slate-900 dark:text-gray-100">
                {{ $product->name }}
                @if($product->shelf_location)
                    <div class="mt-0.5 inline-flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500 font-normal">
                        <i data-lucide="map-pin" class="h-3 w-3"></i> {{ $product->shelf_location }}
                    </div>
                @endif
            </td>
            @can('viewPurchasePrice', $product)
                <td class="p-3 text-slate-700 dark:text-slate-200">{{ number_format($product->purchase_price, 2) }} DH</td>
            @endcan
            <td class="p-3 text-slate-700 dark:text-slate-200">{{ number_format($product->selling_price, 2) }} DH</td>
            <td class="p-3">
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                        <span>{{ $product->stock_quantity }} unités</span>
                        @if($product->isStockCritical())
                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-200">Critique</span>
                        @endif
                    </div>
                    <div class="h-2 rounded-full bg-slate-200 overflow-hidden dark:bg-slate-700">
                        <div class="h-full rounded-full {{ $stockColor }}" style="width: {{ $stockPercent }}%"></div>
                    </div>
                </div>
            </td>
            @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                <td class="p-3">
                    <div class="inline-flex items-center gap-3">
                        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Supprimer ce produit sans historique ?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-rose-600" type="submit">Supprimer</button>
                        </form>
                        <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 font-medium">Modifier</a>
                        <a href="{{ route('products.barcode', $product) }}" target="_blank" title="Imprimer le code-barres" class="inline-flex items-center text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                            <i data-lucide="scan-barcode" class="h-4 w-4"></i>
                        </a>
                    </div>
                </td>
            @endif
        </tr>
    @empty
        <tr><td colspan="5" class="p-6 text-center text-slate-500">Aucun produit trouvé.</td></tr>
    @endforelse
