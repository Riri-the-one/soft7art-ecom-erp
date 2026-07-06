<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold tracking-tight text-white">
            {{ __('Catalogue des Produits') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 rounded-[2rem] bg-gradient-to-r from-purple-600 via-fuchsia-600 to-teal-500 p-8 text-white shadow-[0_30px_60px_-30px_rgba(124,58,237,0.8)]">
                <h1 class="text-3xl font-bold">Catalogue Produits</h1>
                <p class="mt-2 max-w-2xl text-slate-100">Pilotez votre inventaire avec une recherche instantanée et des alertes visuelles de stock critique.</p>
            </div>

            <div class="bg-white/95 overflow-hidden shadow-[0_25px_50px_-25px_rgba(15,23,42,0.25)] sm:rounded-[2rem] border border-slate-200">
                <div class="p-6">
                    {{-- Message de succès après enregistrement --}}
@if(session('success'))
    <div class="mb-6 rounded-3xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700 shadow-sm">
        {{ session('success') }}
    </div>
@endif

                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-3xl bg-gradient-to-r from-purple-600 to-teal-400 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl">
                                + Ajouter un produit
                            </a>
                        @endif
                        <form method="GET" action="{{ route('products.index') }}" class="flex w-full max-w-2xl gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un produit..." class="flex-1 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-200">
                            <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-gradient-to-r from-purple-600 to-teal-400 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl">
                                Rechercher
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-[1.5rem] border border-slate-200 bg-white/90 shadow-sm">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Nom du produit</th>
                                    @if(Auth::user()->can('viewPurchasePrice', \App\Models\Product::class))
                                        <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Prix d'achat</th>
                                    @endif
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Prix de vente</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">En Stock</th>
                                    @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                                        <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                        <td class="p-3 text-slate-900">{{ $product->name }}</td>
                                        @can('viewPurchasePrice', $product)
                                            <td class="p-3 text-slate-700">{{ number_format($product->purchase_price, 2) }} DH</td>
                                        @endcan
                                        <td class="p-3 text-slate-700">{{ number_format($product->selling_price, 2) }} DH</td>
                                        <td class="p-3">
                                            {{-- Utilisation de ta méthode métier pour l'alerte visuelle --}}
                                            @if($product->isStockCritical())
                                                <span class="text-red-600 font-medium bg-red-50 px-2 py-1 rounded-full text-xs">
                                                     {{ $product->stock_quantity }} (Critique)
                                                </span>
                                            @else
                                                <span class="text-slate-700 font-medium">
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