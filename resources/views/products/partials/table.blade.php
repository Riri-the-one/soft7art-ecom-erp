<tbody>
    @foreach($products as $product)
        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
            <td class="p-3 text-slate-900">{{ $product->name }}</td>
            @can('viewPurchasePrice', $product)
                <td class="p-3 text-slate-700">{{ number_format($product->purchase_price, 2) }} DH</td>
            @endcan
            <td class="p-3 text-slate-700">{{ number_format($product->selling_price, 2) }} DH</td>
            <td class="p-3">
                @if($product->isStockCritical())
                    <span class="text-red-600 font-medium bg-red-50 px-2 py-1 rounded-full text-xs">{{ $product->stock_quantity }} (Critique)</span>
                @else
                    <span class="text-slate-700 font-medium">{{ $product->stock_quantity }}</span>
                @endif
            </td>
            @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                <td class="p-3">
                    <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 font-medium">Modifier</a>
                </td>
            @endif
        </tr>
    @endforeach
</tbody>
