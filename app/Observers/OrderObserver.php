<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderActivityLog; // On importe le bon modèle !

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // On vérifie si le statut a changé
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            // Enregistrement infalsifiable dans la bonne table d'historique
            OrderActivityLog::create([
                'order_id' => $order->id,
                'user_id' => auth()->check() ? auth()->id() : $order->user_id, // Si script auto, on prend le proprio
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            // Gestion du stock si la commande est confirmée (Ta logique métier existante)
            if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
                foreach ($order->products as $product) {
                    $quantity = $product->pivot->quantity;
                    $product->decrement('stock_quantity', $quantity);
                }
            }
        }
    }
}