<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alertes de Stock') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Produits en alerte de stock critique</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-200">
                                    <th class="p-3 font-bold text-gray-600">Produit</th>
                                    <th class="p-3 font-bold text-gray-600">Référence</th>
                                    <th class="p-3 font-bold text-gray-600">Prix de vente</th>
                                    <th class="p-3 font-bold text-gray-600">Stock actuel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-semibold text-gray-900">{{ $product->name }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ $product->sku ?? 'N/A' }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ number_format($product->selling_price, 2, ',', ' ') }} DH</td>
                                        <td class="p-3 text-sm font-bold text-red-600">{{ $product->stock_quantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-gray-500 font-medium">
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
