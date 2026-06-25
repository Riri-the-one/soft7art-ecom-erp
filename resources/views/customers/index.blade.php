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
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Liste des Clients</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-200">
                                    <th class="p-3 font-bold text-gray-600">Nom du Client</th>
                                    <th class="p-3 font-bold text-gray-600">Email</th>
                                    <th class="p-3 font-bold text-gray-600">Telephone</th>
                                    <th class="p-3 font-bold text-gray-600 text-center">Commandes</th>
                                    <th class="p-3 font-bold text-gray-600 text-center">Taux de Livraison</th>
                                    <th class="p-3 font-bold text-gray-600 text-right">Total Depense</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="p-3 font-semibold text-gray-900">{{ $customer->name }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ $customer->email }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ $customer->phone ?? 'N/A' }}</td>
                                        <td class="p-3 text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-bold">
                                                {{ $customer->orders_count }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-center">
                                            @if ($customer->delivery_rate < 50)
                                                <span class="text-red-600 font-bold">{{ $customer->delivery_rate }}%</span>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Risque élevé</span>
                                            @elseif ($customer->delivery_rate < 80)
                                                <span class="text-orange-500">{{ $customer->delivery_rate }}%</span>
                                            @else
                                                <span class="text-green-600 font-bold">{{ $customer->delivery_rate }}%</span>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Client sérieux</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-right font-bold text-indigo-600">
                                            {{ number_format($customer->orders->sum('total_amount'), 2, ',', ' ') }} DH
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-6 text-center text-gray-500 font-medium">
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
