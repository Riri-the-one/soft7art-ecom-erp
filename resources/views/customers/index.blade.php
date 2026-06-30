<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Clients CRM') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Liste des Clients</h3>
                        <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Nouveau Client
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Nom du Client</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Email</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider">Telephone</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider text-center">Commandes</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider text-center">Taux de Livraison</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider text-right">Total Depense</th>
                                    <th class="p-3 font-semibold text-gray-400 text-xs uppercase tracking-wider text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                        <td class="p-3 font-semibold text-gray-800">{{ $customer->name }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $customer->email }}</td>
                                        <td class="p-3 text-sm text-gray-400">{{ $customer->phone ?? 'N/A' }}</td>
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
                                        <td class="p-3 text-right font-bold text-gray-800">
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
                                        <td colspan="7" class="p-6 text-center text-gray-400 font-medium">
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
</x-app-layout>
