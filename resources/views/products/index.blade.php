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
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-200">
                                    <th class="p-3 font-bold text-gray-600">Nom du produit</th>
                                    <th class="p-3 font-bold text-gray-600">Prix d'achat</th>
                                    <th class="p-3 font-bold text-gray-600">Prix de vente</th>
                                    <th class="p-3 font-bold text-gray-600">En Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $product->name }}</td>
                                        <td class="p-3">{{ number_format($product->purchase_price, 2) }} DH</td>
                                        <td class="p-3">{{ number_format($product->selling_price, 2) }} DH</td>
                                        <td class="p-3">
                                            {{-- Utilisation de ta méthode métier pour l'alerte visuelle --}}
                                            @if($product->isStockCritical())
                                                <span class="text-red-600 font-bold bg-red-100 px-2 py-1 rounded">
                                                     {{ $product->stock_quantity }} (Critique)
                                                </span>
                                            @else
                                                <span class="text-green-600 font-bold">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            @endif
                                        </td>
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