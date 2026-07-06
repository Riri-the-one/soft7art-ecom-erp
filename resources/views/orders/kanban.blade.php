<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-white">
                {{ __('Kanban des commandes') }}
            </h2>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center rounded-3xl bg-slate-950/95 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-slate-900">
                &larr; Retour à la vue liste
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 rounded-[2rem] bg-gradient-to-r from-purple-600 via-fuchsia-600 to-teal-500 p-8 text-white shadow-[0_30px_60px_-30px_rgba(124,58,237,0.8)]">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Kanban des commandes</h1>
                        <p class="mt-2 max-w-2xl text-slate-100">Déplacez les commandes pour mettre à jour les statuts en temps réel.</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center rounded-3xl bg-slate-950/95 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-slate-900">
                        &larr; Retour à la liste
                    </a>
                </div>
            </div>

            <div class="grid gap-4 grid-cols-1 md:grid-cols-3 lg:grid-cols-5">
                @foreach(['pending' => 'En attente', 'confirmed' => 'Confirmées', 'shipped' => 'Expédiées', 'delivered' => 'Livrées', 'canceled' => 'Annulées'] as $status => $label)
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white/95 shadow-[0_20px_40px_-20px_rgba(15,23,42,0.15)] p-5">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-700">{{ $label }}</h3>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ $orders->where('status', $status)->count() }}
                            </span>
                        </div>
                        
                        <div class="space-y-3 kanban-column min-h-[520px] rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50/80 p-3 transition-colors" data-status="{{ $status }}">
                            @foreach($orders->where('status', $status) as $order)
                                <div class="kanban-card rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_15px_30px_-15px_rgba(15,23,42,0.12)] p-4 transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_25px_45px_-20px_rgba(15,23,42,0.15)] cursor-move" data-order-id="{{ $order->id }}">
                                    <div class="flex justify-between items-start gap-3 mb-3">
                                        <div>
                                            <a href="{{ route('orders.show', $order) }}" class="font-semibold text-purple-600 hover:text-teal-500 hover:underline transition-colors">#{{ $order->id }}</a>
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $order->created_at->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-slate-900 mb-1">{{ $order->customer->name }}</div>
                                    <div class="text-sm text-teal-600 font-bold">{{ number_format($order->total_amount, 2, ',', ' ') }} DH</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

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
                this.classList.add('bg-slate-200', 'rounded-lg');
            });

            column.addEventListener('dragleave', function () {
                this.classList.remove('bg-slate-200', 'rounded-lg');
            });

            column.addEventListener('drop', function () {
                this.classList.remove('bg-slate-200', 'rounded-lg');
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
</x-app-layout>
