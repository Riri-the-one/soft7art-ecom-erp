<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Ajouter un nouveau produit') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 p-6 dark:bg-slate-900 dark:border-slate-800">

                <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                    @csrf {{-- Protection obligatoire contre les failles CSRF --}}

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du produit</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optionnelle)</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix d'achat (DH)</label>
                            <input type="number" step="0.01" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">
                            @error('purchase_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix de vente (DH)</label>
                            <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">
                            @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité en Stock</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">
                            @error('stock_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="shelf_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emplacement en rayon (Optionnel)</label>
                        <input type="text" name="shelf_location" id="shelf_location" placeholder="Ex: Rayon A-02" value="{{ old('shelf_location') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100">
                        @error('shelf_location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t dark:border-slate-800">
                        <a href="{{ route('products.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">Annuler</a>
                        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:shadow-lg text-white font-semibold py-2 px-4 rounded-lg shadow-md shadow-indigo-200/50 dark:shadow-none transition-all border-0">
                            <i data-lucide="check" class="h-4 w-4"></i> Enregistrer le produit
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
