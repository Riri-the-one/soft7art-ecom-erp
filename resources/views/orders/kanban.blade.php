<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-slate-800 dark:text-white leading-tight">
                {{ __('Kanban des commandes') }}
            </h2>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                &larr; Retour à la vue liste
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            <div class="grid gap-4 grid-cols-1 md:grid-cols-3 lg:grid-cols-5">
                @foreach(['pending' => 'En attente', 'confirmed' => 'Confirmées', 'shipped' => 'Expédiées', 'delivered' => 'Livrées', 'canceled' => 'Annulées'] as $status => $label)
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 dark:bg-slate-900 dark:border-slate-800">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">{{ $label }}</h3>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                                {{ $orders->where('status', $status)->count() }}
                            </span>
                        </div>

                        <div class="space-y-3 kanban-column min-h-[520px] rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-3 transition-colors dark:border-slate-700 dark:bg-slate-950/80" data-status="{{ $status }}">
                            @forelse($orders->where('status', $status) as $order)
                                <div class="kanban-card rounded-xl border border-slate-200 bg-white shadow-sm p-4 transition duration-200 hover:-translate-y-0.5 hover:shadow-md cursor-move dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800" data-order-id="{{ $order->id }}">
                                    <div class="flex justify-between items-start gap-3 mb-3">
                                        <div>
                                            <a href="{{ route('orders.show', $order) }}" class="font-semibold text-indigo-600 hover:text-violet-500 hover:underline transition-colors">#{{ $order->id }}</a>
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-slate-900 mb-1 dark:text-gray-100">{{ $order->customer->name }}</div>
                                    <div class="text-sm text-teal-600 font-bold dark:text-teal-300">{{ number_format($order->total_amount, 2, ',', ' ') }} DH</div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                                    <svg class="mx-auto h-16 w-16" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 14H46V50H18V14Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M22 20H42" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                        <path d="M22 28H42" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                        <path d="M22 36H34" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                    <p class="mt-4 font-semibold text-slate-900 dark:text-gray-100">Aucune commande dans cette colonne</p>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Glissez une commande ici pour changer son statut.</p>
                                </div>
                            @endforelse
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
