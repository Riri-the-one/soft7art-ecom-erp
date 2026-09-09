<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Créer une nouvelle commande') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                <div class="p-6 text-slate-900 dark:text-gray-100">
                    <div class="mb-6">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-200 dark:hover:bg-slate-800">
                            ← Retour à la liste
                        </a>
                    </div>

                    <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sélection du client -->
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-2 dark:text-gray-300">Client</label>
                                <select
                                    id="customer_id"
                                    name="customer_id"
                                    required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100"
                                >
                                    <option value="">Sélectionner un client</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sélection du produit -->
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-slate-700 mb-2 dark:text-gray-300">Produit</label>
                                <select
                                    id="product_id"
                                    name="product_id"
                                    required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100"
                                >
                                    <option value="">Sélectionner un produit</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                            {{ $product->name }} - {{ number_format($product->selling_price, 2) }} DH (Stock: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantité -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-slate-700 mb-2 dark:text-gray-300">Quantité</label>
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    min="1"
                                    value="1"
                                    required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100"
                                >
                            </div>

                            <!-- Frais de livraison -->
                            <div>
                                <label for="delivery_fee" class="block text-sm font-medium text-slate-700 mb-2 dark:text-gray-300">Frais de livraison (DH)</label>
                                <input
                                    type="number"
                                    id="delivery_fee"
                                    name="delivery_fee"
                                    min="0"
                                    step="0.01"
                                    value="0"
                                    required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-700 dark:text-gray-100"
                                >
                            </div>
                        </div>

                        <!-- Avertissement de doublon (masqué par défaut, affiché en AJAX) -->
                        <div id="duplicate-warning" hidden class="flex items-start gap-3 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                            <i data-lucide="triangle-alert" class="h-5 w-5 flex-shrink-0 mt-0.5"></i>
                            <span id="duplicate-warning-text"></span>
                        </div>

                        <!-- Récapitulatif -->
                        <div class="bg-slate-50 rounded-xl p-4 dark:bg-slate-950">
                            <h4 class="text-sm font-semibold text-slate-700 mb-2 dark:text-gray-300">Récapitulatif</h4>
                            <div class="space-y-1 text-sm text-slate-600 dark:text-gray-400">
                                <p>Sous-total : <span id="subtotal" class="font-medium">0.00</span> DH</p>
                                <p>Frais de livraison : <span id="delivery_fee_display" class="font-medium">0.00</span> DH</p>
                                <p class="text-base font-semibold text-slate-900 dark:text-gray-100">Total : <span id="total" class="font-bold">0.00</span> DH</p>
                            </div>
                        </div>

                        <!-- bouton de soumission -->
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:shadow-lg text-white font-semibold py-2 px-4 rounded-lg shadow-md shadow-indigo-200/50 dark:shadow-none transition-all border-0"
                            >
                                <i data-lucide="check" class="h-4 w-4"></i> Enregistrer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const deliveryFeeInput = document.getElementById('delivery_fee');
    const subtotalDisplay = document.getElementById('subtotal');
    const deliveryFeeDisplay = document.getElementById('delivery_fee_display');
    const totalDisplay = document.getElementById('total');

    function calculateTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption?.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const deliveryFee = parseFloat(deliveryFeeInput.value) || 0;

        const subtotal = price * quantity;
        const total = subtotal + deliveryFee;

        subtotalDisplay.textContent = subtotal.toFixed(2);
        deliveryFeeDisplay.textContent = deliveryFee.toFixed(2);
        totalDisplay.textContent = total.toFixed(2);
    }

    productSelect.addEventListener('change', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);
    deliveryFeeInput.addEventListener('input', calculateTotal);

    // --- Détection de doublon de commande ---
    const customerSelect = document.getElementById('customer_id');
    const duplicateWarning = document.getElementById('duplicate-warning');
    const duplicateWarningText = document.getElementById('duplicate-warning-text');

    async function checkDuplicate() {
        const customerId = customerSelect.value;
        const productId = productSelect.value;

        if (!customerId || !productId) {
            duplicateWarning.hidden = true;
            return;
        }

        try {
            const response = await fetch('{{ route("orders.checkDuplicate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ customer_id: customerId, product_id: productId }),
            });

            const data = await response.json();

            if (data.duplicate) {
                duplicateWarningText.textContent = `Attention : ce client a déjà passé une commande similaire il y a ${data.minutes_ago} minute(s) (commande #${data.order_id}). Vérifiez avant de valider pour éviter un doublon.`;
                duplicateWarning.hidden = false;
            } else {
                duplicateWarning.hidden = true;
            }
        } catch (error) {
            console.error('Erreur lors de la vérification de doublon:', error);
        }
    }

    customerSelect.addEventListener('change', checkDuplicate);
    productSelect.addEventListener('change', checkDuplicate);
</script>
