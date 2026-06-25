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
        }
    }

    // Tu peux laisser les autres méthodes générées par défaut (created, deleted, etc.) vides en dessous.
}