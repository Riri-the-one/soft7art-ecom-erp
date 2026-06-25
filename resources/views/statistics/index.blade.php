<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistiques et Chiffre d\'Affaires') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Barre de filtres temporels -->
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <a href="?filter=today" class="{{ $filter === 'today' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Aujourd'hui</a>
                <a href="?filter=week" class="{{ $filter === 'week' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Cette semaine</a>
                <a href="?filter=month" class="{{ $filter === 'month' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Ce mois-ci</a>
                <a href="?filter=all" class="{{ $filter === 'all' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Global</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Chiffre d'affaires</p>
                    <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($totalRevenue, 2) }} DH</p>
                    <p class="mt-3 text-sm text-gray-500">Total des commandes livrées pendant la période.</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Total des commandes</p>
                    <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalOrders }}</p>
                    <p class="mt-3 text-sm text-gray-500">Nombre total de commandes prises en compte.</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Commandes en attente</p>
                    <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $pendingOrders }}</p>
                    <p class="mt-3 text-sm text-gray-500">Commandes qui attendent encore traitement.</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Bénéfice Net</p>
                    <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($netProfit, 2) }} DH</p>
                    <p class="mt-3 text-sm text-gray-500">Prix de vente - Prix d'achat - Frais de livraison</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Évolution des commandes</h3>
                <canvas id="ordersChart" height="100"></canvas>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-8">
                <a href="{{ route('products.index', ['alert' => 'true']) }}" class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Produits en alerte</p>
                    <p class="text-3xl text-red-600 font-bold tracking-tight">{{ $lowStockProducts }}</p>
                    <p class="mt-3 text-sm text-gray-500">Produits avec un stock inférieur à 10 unités.</p>
                </a>
            </div>

            <div class="mt-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-sm text-gray-600">Les chiffres ci-dessus sont basés sur les commandes et le stock actuels.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Commandes',
                    data: @json($chartData),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                    },
                },
            },
        });
    </script>
</x-app-layout>