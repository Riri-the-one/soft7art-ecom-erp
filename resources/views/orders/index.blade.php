<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suivi des Commandes Clients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Formulaire de recherche et filtrage -->
                    <form method="GET" action="{{ route('orders.index') }}" class="mb-6" id="search-form">
                        <div class="flex flex-wrap gap-4 items-end">
                            <!-- Champ de recherche -->
                            <div class="flex-1 min-w-xs">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search"
                                    placeholder="N° commande ou nom client..."
                                    value="{{ request('search') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                            </div>

                            <!-- Sélecteur de statut -->
                            <div class="flex-1 min-w-xs">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <select 
                                    name="status" 
                                    id="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex flex-wrap gap-2 items-end">
                                <a 
                                    href="{{ route('orders.index') }}" 
                                    class="px-6 py-2 bg-gray-300 text-gray-900 font-medium rounded-md text-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Réinitialiser
                                </a>
                                <a 
                                    href="{{ route('orders.export') }}" 
                                    class="px-6 py-2 bg-gray-900 text-white font-medium rounded-md text-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700"
                                >
                                    Exporter en CSV
                                </a>
                            </div>
                        </div>
                    </form>

                    <div id="table-container">
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
    const tableContainer = document.getElementById('table-container');

    // Fonction pour effectuer la recherche en direct
    const performSearch = debounce(async function() {
        const searchValue = searchInput.value;
        const statusValue = statusSelect.value;

        try {
            const response = await fetch('{{ route("orders.index") }}?' + new URLSearchParams({
                search: searchValue,
                status: statusValue
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const html = await response.text();
                tableContainer.innerHTML = html;
            }
        } catch (error) {
            console.error('Erreur lors de la recherche:', error);
        }
    }, 300); // Délai de 300ms

    // Écoute de l'événement input sur les champs de recherche et statut
    searchInput.addEventListener('input', performSearch);
    statusSelect.addEventListener('change', performSearch);
</script>
