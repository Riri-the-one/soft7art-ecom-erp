<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white leading-tight">
            {{ __('Catalogue des Produits') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:bg-slate-900 dark:border-slate-800 dark:text-gray-100">
                <div class="p-6">
                    {{-- Message de succès après enregistrement --}}
@if(session('success'))
    <div class="mb-6 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-teal-700 dark:border-teal-700 dark:bg-teal-900/25 dark:text-teal-100">
        {{ session('success') }}
    </div>
@endif

                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        @if(Auth::user()->hasRole('stock_manager') || Auth::user()->hasRole('super_admin'))
                            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 justify-center rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-200/50 dark:shadow-none transition hover:shadow-lg">
                                <i data-lucide="plus" class="h-4 w-4"></i> Ajouter un produit
                            </a>
                        @endif
                        <form method="GET" action="{{ route('products.index') }}" class="flex w-full max-w-2xl gap-2">
                            <div class="relative flex-1">
                                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                                <input id="searchInput" type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un produit..." class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2.5 text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40">
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                Rechercher
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white/90 dark:bg-slate-950 dark:border-slate-800">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900">
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider dark:text-slate-400">Nom du produit</th>
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
                            @include('products.partials.table')
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('searchInput');
            const tableBody = document.querySelector('table tbody');

            if (!input || !tableBody) return;

            let timeout;
            input.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    const query = input.value.trim();
                    const url = new URL("{{ route('products.index') }}", window.location.origin);
                    if (query) url.searchParams.set('search', query);

                    const response = await fetch(url.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) return;
                    const html = await response.text();
                    tableBody.innerHTML = html;
                    if (window.lucide) window.lucide.createIcons();
                }, 250);
            });
        });
    </script>
</x-app-layout>
