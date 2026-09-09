<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Statistiques et Chiffre d\'Affaires') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Barre de filtres temporels -->
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <a href="?filter=today" class="{{ $filter === 'today' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 dark:border-slate-700 text-slate-600 dark:text-gray-100 border border-slate-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Aujourd'hui</a>
                <a href="?filter=week" class="{{ $filter === 'week' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 dark:border-slate-700 text-slate-600 dark:text-gray-100 border border-slate-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Cette semaine</a>
                <a href="?filter=month" class="{{ $filter === 'month' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 dark:border-slate-700 text-slate-600 dark:text-gray-100 border border-slate-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Ce mois-ci</a>
                <a href="?filter=all" class="{{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-slate-900 dark:border-slate-700 text-slate-600 dark:text-gray-100 border border-slate-200' }} transition-colors duration-200 rounded-full px-4 py-2 text-sm font-medium">Global</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider dark:text-gray-100">Chiffre d'affaires</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                            <i data-lucide="banknote" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-gray-100">{{ number_format($totalRevenue, 2) }} DH</p>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Total des commandes livrées pendant la période.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider dark:text-gray-100">Total des commandes</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                            <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-gray-100">{{ $totalOrders }}</p>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Nombre total de commandes prises en compte.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider dark:text-gray-100">Commandes en attente</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                            <i data-lucide="clock" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-gray-100">{{ $pendingOrders }}</p>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Commandes qui attendent encore traitement.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider dark:text-gray-100">Bénéfice Net</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                            <i data-lucide="trending-up" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-gray-100">{{ number_format($netProfit, 2) }} DH</p>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Prix de vente - Prix d'achat - Frais de livraison</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-slate-100 p-6 shadow-sm mt-8">
                <div class="flex items-center gap-2 mb-6">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                        <i data-lucide="line-chart" class="h-4 w-4"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-gray-100">Évolution des commandes</h3>
                </div>
                <canvas id="ordersChart" height="100"></canvas>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-8 mt-8">
                <a href="{{ route('products.index', ['alert' => 'true']) }}" class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider dark:text-gray-100">Produits en alerte</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                            <i data-lucide="triangle-alert" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <p class="text-3xl text-red-600 font-bold tracking-tight dark:text-red-300">{{ $lowStockProducts }}</p>
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-300">Produits avec un stock inférieur à 10 unités.</p>
                </a>
            </div>

            <div class="mt-8">
                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-sm text-slate-600 dark:text-slate-300">Les chiffres ci-dessus sont basés sur les commandes et le stock actuels.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Commandes',
                    data: @json($chartData),
                    borderColor: '#6366F1',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#6366F1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    </script>
</x-app-layout>
