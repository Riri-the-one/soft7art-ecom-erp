<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white leading-tight">
            {{ __('Suivi des Commandes Clients') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:bg-slate-900 dark:border-slate-800 dark:text-gray-100">
                <div class="p-6">

                    <!-- Formulaire de recherche et filtrage -->
                    <form method="GET" action="{{ route('orders.index') }}" class="mb-6" id="search-form">
                        <div class="grid gap-4 lg:grid-cols-5">
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-semibold text-slate-600 mb-2 dark:text-slate-300">Rechercher</label>
                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    placeholder="N° commande ou client..."
                                    value="{{ request('search') }}"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40"
                                >
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-600 mb-2 dark:text-slate-300">Statut</label>
                                <select
                                    name="status"
                                    id="status"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40"
                                >
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Retournée</option>
                                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_from" class="block text-sm font-semibold text-slate-600 mb-2 dark:text-slate-300">Du</label>
                                <input
                                    type="date"
                                    name="date_from"
                                    id="date_from"
                                    value="{{ request('date_from') }}"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40"
                                >
                            </div>

                            <!-- Date de fin -->
                            <div>
                                <label for="date_to" class="block text-sm font-semibold text-slate-600 mb-2 dark:text-slate-300">Au</label>
                                <input
                                    type="date"
                                    name="date_to"
                                    id="date_to"
                                    value="{{ request('date_to') }}"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40"
                                >
                            </div>

                            <!-- Boutons d'action -->
                            <div class="lg:col-span-5 flex flex-wrap gap-3 items-center justify-between">
                                <div class="flex flex-wrap gap-3">
                                    @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('agent'))
                                        <a
                                            href="{{ route('orders.create') }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-semibold px-4 py-2.5 text-sm shadow-md shadow-indigo-200/50 dark:shadow-none transition hover:shadow-lg"
                                        >
                                            <i data-lucide="plus" class="h-4 w-4"></i> Nouvelle Commande
                                        </a>
                                    @endif
                                    <a href="{{ route('orders.kanban') }}" class="inline-flex items-center rounded-xl bg-indigo-600 text-white font-semibold px-4 py-2.5 text-sm shadow-sm transition hover:bg-indigo-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                                        </svg>
                                        Vue Kanban
                                    </a>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <a
                                        href="{{ route('orders.index') }}"
                                        class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 text-slate-700 px-4 py-2.5 text-sm font-medium hover:bg-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-700"
                                    >
                                        Réinitialiser
                                    </a>
                                    @if(in_array(auth()->user()->role, ['super_admin', 'agent']))
                                        <a
                                            href="{{ route('orders.export') }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-semibold px-4 py-2.5 text-sm shadow-md shadow-indigo-200/50 dark:shadow-none transition hover:shadow-lg"
                                        >
                                            <i data-lucide="download" class="h-4 w-4"></i> Exporter en CSV
                                        </a>
                                        <a
                                            href="{{ route('orders.export.logistics') }}"
                                            title="Export formaté pour les transporteurs (Cathedis, EcoTrack, Ozone...) — commandes confirmées uniquement"
                                            class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-white text-indigo-600 font-semibold px-4 py-2.5 text-sm shadow-sm transition hover:bg-indigo-50 dark:bg-slate-950 dark:border-slate-700 dark:text-indigo-300 dark:hover:bg-slate-800"
                                        >
                                            <i data-lucide="truck" class="h-4 w-4"></i> Export Logistique
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="table-container" class="rounded-xl border border-slate-200 bg-slate-50 overflow-hidden dark:bg-slate-950 dark:border-slate-800">
                        @include('orders.partials.table')
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Fonction utilitaire pour le debounce
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), delay);
        };
    }

    // Récupération des champs de recherche et filtrage
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    const tableContainer = document.getElementById('table-container');

    // Fonction pour effectuer la recherche en direct
    const performSearch = debounce(async function() {
        const searchValue = searchInput.value;
        const statusValue = statusSelect.value;
        const dateFromValue = dateFromInput.value;
        const dateToValue = dateToInput.value;

        try {
            const response = await fetch('{{ route("orders.index") }}?' + new URLSearchParams({
                search: searchValue,
                status: statusValue,
                date_from: dateFromValue,
                date_to: dateToValue
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const html = await response.text();
                tableContainer.innerHTML = html;
                if (window.lucide) window.lucide.createIcons();
            }
        } catch (error) {
            console.error('Erreur lors de la recherche:', error);
        }
    }, 300); // Délai de 300ms

    // Écoute de l'événement input sur les champs de recherche et statut
    searchInput.addEventListener('input', performSearch);
    statusSelect.addEventListener('change', performSearch);
    dateFromInput.addEventListener('change', performSearch);
    dateToInput.addEventListener('change', performSearch);
</script>
