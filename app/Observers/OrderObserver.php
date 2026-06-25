<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderActivity;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // On vérifie si le champ 'status' a spécifiquement été modifié
        if ($order->isDirty('status')) {
            OrderActivity::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $order->getOriginal('status'),
                'new_status' => $order->status,
            ]);

            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
                foreach ($order->products as $product) {
                    $product->decrement('stock_quantity', $product->pivot->quantity);
                }
            }

            if (in_array($newStatus, ['canceled', 'returned']) && in_array($oldStatus, ['confirmed', 'shipped', 'delivered'])) {
                foreach ($order->products as $product) {
                    $product->increment('stock_quantity', $product->pivot->quantity);
                }
            }
        }
    }

    // Tu peux laisser les autres méthodes générées par défaut (created, deleted, etc.) vides en dessous.
}