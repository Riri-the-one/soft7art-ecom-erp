<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold tracking-tight text-white">
            {{ __('Gestion des Clients CRM') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 rounded-[2rem] bg-gradient-to-r from-violet-600 via-purple-600 to-teal-500 p-8 text-white shadow-[0_30px_60px_-30px_rgba(124,58,237,0.8)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Clients CRM</h1>
                        <p class="mt-2 max-w-2xl text-slate-100">Maintenez un suivi client propre et trouvez rapidement le bon profil.</p>
                    </div>
                    <a href="{{ route('customers.create') }}" class="inline-flex items-center justify-center rounded-3xl bg-white/95 px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-white">
                        Nouveau Client
                    </a>
                </div>
            </div>

            <div class="bg-white/95 overflow-hidden shadow-[0_25px_50px_-25px_rgba(15,23,42,0.25)] sm:rounded-[2rem] border border-slate-200">
                <div class="p-6">
                    <form method="GET" action="{{ route('customers.index') }}" class="mb-6 flex flex-col gap-4 md:flex-row md:items-center">
                        <input id="searchInput" type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un client..." class="flex-1 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 shadow-sm focus:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-200">
                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-gradient-to-r from-purple-600 to-teal-400 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl">Rechercher</button>
                    </form>

                    <div class="overflow-x-auto rounded-[1.5rem] border border-slate-200 bg-white/90 shadow-sm">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Nom du Client</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Email</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider">Telephone</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider text-center">Commandes</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider text-center">Taux de Livraison</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider text-right">Total Depense</th>
                                    <th class="p-3 font-semibold text-slate-500 text-xs uppercase tracking-wider text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                        <td class="p-3 font-semibold text-slate-900">
                                            <a href="{{ route('customers.show', $customer) }}" class="text-purple-600 hover:underline transition-colors">{{ $customer->name }}</a>
                                        </td>
                                        <td class="p-3 text-sm text-slate-700">{{ $customer->email }}</td>
                                        <td class="p-3 text-sm text-slate-500">{{ $customer->phone ?? 'N/A' }}</td>
                                        <td class="p-3 text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-sm font-medium">
                                                {{ $customer->orders_count }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-center">
                                            @if ($customer->delivery_rate < 50)
                                                <span class="text-red-600 font-medium">{{ $customer->delivery_rate }}%</span>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-red-50 text-red-600 rounded-full text-xs font-medium">Risque élevé</span>
                                            @elseif ($customer->delivery_rate < 80)
                                                <span class="text-orange-500 font-medium">{{ $customer->delivery_rate }}%</span>
                                            @else
                                                <span class="text-teal-600 font-medium">{{ $customer->delivery_rate }}%</span>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-teal-50 text-teal-600 rounded-full text-xs font-medium">Client sérieux</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-right font-bold text-slate-900">
                                            {{ number_format($customer->orders->sum('total_amount'), 2, ',', ' ') }} DH
                                        </td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center text-xs text-purple-600 hover:text-purple-700 font-medium">
                                                Détails
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-6 text-center text-slate-500 font-medium">
                                            Aucun client trouvé dans la base de donnees.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $customers->links() }}
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
                    const url = new URL("{{ route('customers.index') }}", window.location.origin);
                    if (query) url.searchParams.set('search', query);

                    const response = await fetch(url.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) return;
                    const html = await response.text();
                    tableBody.innerHTML = html;
                }, 250);
            });
        });
    </script>
</x-app-layout>
