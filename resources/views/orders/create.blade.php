<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une nouvelle commande') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            ← Retour à la liste
                        </a>
                    </div>

                    <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sélection du client -->
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                                <select 
                                    id="customer_id" 
                                    name="customer_id" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <option value="">Sélectionner un client</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sélection du produit -->
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Produit</label>
                                <select 
                                    id="product_id" 
                                    name="product_id" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantité</label>
                                <input 
                                    type="number" 
                                    id="quantity" 
                                    name="quantity" 
                                    min="1" 
                                    value="1"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                            </div>

                            <!-- Frais de livraison -->
                            <div>
                                <label for="delivery_fee" class="block text-sm font-medium text-gray-700 mb-2">Frais de livraison (DH)</label>
                                <input 
                                    type="number" 
                                    id="delivery_fee" 
                                    name="delivery_fee" 
                                    min="0" 
                                    step="0.01"
                                    value="0"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                            </div>
                        </div>

                        <!-- Récapitulatif -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Récapitulatif</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>Sous-total : <span id="subtotal" class="font-medium">0.00</span> DH</p>
                                <p>Frais de livraison : <span id="delivery_fee_display" class="font-medium">0.00</span> DH</p>
                                <p class="text-base font-semibold text-gray-900">Total : <span id="total" class="font-bold">0.00</span> DH</p>
                            </div>
                        </div>

                        <!-- bouton de soumission -->
                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Enregistrer la commande
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
</script>
