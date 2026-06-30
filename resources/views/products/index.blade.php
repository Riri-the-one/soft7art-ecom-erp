<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catalogue des Produits') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Message de succès après enregistrement --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Bouton visible uniquement par le Gestionnaire ou le Super Admin --}}
@if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
    <div class="mb-6 flex justify-end">
        <a href="{{ route('products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
            + Ajouter un produit
        </a>
    </div>
@endif
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Nom du produit</th>
                                    @if(Auth::user()->can('viewPurchasePrice', \App\Models\Product::class))
                                        <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Prix d'achat</th>
                                    @endif
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Prix de vente</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">En Stock</th>
                                    @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                                        <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                                        <td class="p-3 text-gray-800">{{ $product->name }}</td>
                                        @can('viewPurchasePrice', $product)
                                            <td class="p-3 text-gray-700">{{ number_format($product->purchase_price, 2) }} DH</td>
                                        @endcan
                                        <td class="p-3 text-gray-700">{{ number_format($product->selling_price, 2) }} DH</td>
                                        <td class="p-3">
                                            {{-- Utilisation de ta méthode métier pour l'alerte visuelle --}}
                                            @if($product->isStockCritical())
                                                <span class="text-red-600 font-medium bg-red-50 px-2 py-1 rounded-full text-xs">
                                                     {{ $product->stock_quantity }} (Critique)
                                                </span>
                                            @else
                                                <span class="text-gray-700 font-medium">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                                            <td class="p-3">
                                                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 font-medium">
                                                    Modifier
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Les boutons de pagination (Suivant / Précédent) générés automatiquement ! --}}
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>