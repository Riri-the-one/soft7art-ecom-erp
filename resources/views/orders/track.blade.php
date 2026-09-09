<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi de commande #{{ $order->id }}</title>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.462.0/dist/umd/lucide.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">

    <div class="w-full max-w-lg">

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white mb-3">
                <i data-lucide="package" class="h-6 w-6"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-900">Suivi de votre commande</h1>
            <p class="text-slate-500 text-sm mt-1">Commande #{{ $order->id }} — {{ $order->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            @if(in_array($order->status, ['canceled', 'returned']))
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-center mb-2">
                    <i data-lucide="x-circle" class="h-6 w-6 text-rose-600 mx-auto mb-2"></i>
                    <p class="font-semibold text-rose-700">
                        {{ $order->status === 'canceled' ? 'Cette commande a été annulée.' : 'Cette commande a été retournée.' }}
                    </p>
                </div>
            @else
                @php
                    $steps = [
                        'confirmed' => ['label' => 'Confirmée', 'icon' => 'check'],
                        'shipped' => ['label' => 'Expédiée', 'icon' => 'truck'],
                        'delivered' => ['label' => 'Livrée', 'icon' => 'package-check'],
                    ];
                    $order_position = array_search($order->status, array_keys($steps));
                    $currentIndex = $order_position === false ? -1 : $order_position;
                @endphp

                @if($order->status === 'pending')
                    <p class="text-center text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg py-2 px-3 mb-6">
                        Votre commande est en attente de confirmation par notre équipe.
                    </p>
                @endif

                <div class="flex items-center justify-between mb-2">
                    @foreach($steps as $key => $step)
                        @php $isDone = $currentIndex >= $loop->index; @endphp
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ $isDone ? 'bg-gradient-to-r from-indigo-500 to-violet-600 border-transparent text-white' : 'bg-slate-100 border-slate-300 text-slate-400' }}">
                                <i data-lucide="{{ $step['icon'] }}" class="h-4 w-4"></i>
                            </div>
                            <span class="mt-2 text-xs font-medium {{ $isDone ? 'text-slate-900' : 'text-slate-400' }} text-center">{{ $step['label'] }}</span>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-0.5 -mt-6 {{ $currentIndex > $loop->index ? 'bg-gradient-to-r from-indigo-500 to-violet-600' : 'bg-slate-200' }}"></div>
                        @endif
                    @endforeach
                </div>
            @endif

            <div class="mt-6 border-t border-slate-100 pt-4 space-y-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Articles</p>
                    @foreach($order->products as $product)
                        <div class="flex justify-between text-sm text-slate-700 py-0.5">
                            <span>{{ $product->name }} x{{ $product->pivot->quantity }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                    <span class="text-sm font-medium text-slate-600">Montant total</span>
                    <span class="text-lg font-bold text-slate-900">{{ number_format($order->total_amount, 2) }} DH</span>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">Ce lien est personnel, ne le partagez pas.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => window.lucide && window.lucide.createIcons());
        document.addEventListener('alpine:initialized', () => window.lucide && window.lucide.createIcons());
        window.addEventListener('load', () => window.lucide && window.lucide.createIcons());
    </script>
</body>
</html>
