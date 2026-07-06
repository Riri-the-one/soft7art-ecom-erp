<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kanban des commandes') }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                &larr; Retour à la vue liste
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 grid-cols-1 md:grid-cols-3 lg:grid-cols-5">
                @foreach(['pending' => 'En attente', 'confirmed' => 'Confirmées', 'shipped' => 'Expédiées', 'delivered' => 'Livrées', 'canceled' => 'Annulées'] as $status => $label)
                    <div class="bg-gray-100 rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">{{ $label }}</h3>
                            <span class="bg-gray-200 text-gray-600 py-1 px-2 rounded-full text-xs font-semibold">
                                {{ $orders->where('status', $status)->count() }}
                            </span>
                        </div>
                        
                        <div class="space-y-3 kanban-column min-h-[500px]" data-status="{{ $status }}">
                            @foreach($orders->where('status', $status) as $order)
                                <div class="kanban-card bg-white border border-gray-200 rounded-md p-3 cursor-move shadow-sm hover:shadow transition-shadow" data-order-id="{{ $order->id }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="font-semibold text-indigo-600">#{{ $order->id }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 mb-1">{{ $order->customer->name }}</div>
                                    <div class="text-sm text-gray-600 font-bold">{{ number_format($order->total_amount, 2, ',', ' ') }} DH</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const columns = document.querySelectorAll('.kanban-column');
        let draggedCard = null;

        document.querySelectorAll('.kanban-card').forEach(card => {
            card.draggable = true;

            card.addEventListener('dragstart', function () {
                draggedCard = this;
                this.classList.add('opacity-50');
            });

            card.addEventListener('dragend', function () {
                this.classList.remove('opacity-50');
                draggedCard = null;
            });
        });

        columns.forEach(column => {
            column.addEventListener('dragover', function (event) {
                event.preventDefault();
                this.classList.add('bg-gray-200', 'rounded-lg');
            });

            column.addEventListener('dragleave', function () {
                this.classList.remove('bg-gray-200', 'rounded-lg');
            });

            column.addEventListener('drop', function () {
                this.classList.remove('bg-gray-200', 'rounded-lg');
                if (!draggedCard) return;

                this.appendChild(draggedCard);

                const orderId = draggedCard.getAttribute('data-order-id');
                const newStatus = this.getAttribute('data-status');

                fetch(`/orders/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                }).then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    window.location.reload(); 
                }).catch(error => {
                    console.error(error);
                    alert('Impossible de changer le statut.');
                    window.location.reload();
                });
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
