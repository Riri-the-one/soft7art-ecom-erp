<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderActivityLog;
use App\Models\Product;
use App\Services\OrderStatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderObserver
{
    public function updating(Order $order): void
    {
        if (! $order->isDirty('status')) {
            return;
        }
        if (DB::transactionLevel() === 0 || ! in_array($order->status, OrderStatusService::TRANSITIONS[$order->getOriginal('status')] ?? [], true)) {
            throw ValidationException::withMessages(['status' => 'Transition non autorisée.']);
        }
        $reserve = $order->status === 'confirmed';
        $restore = in_array($order->status, ['canceled', 'returned'], true)
            && in_array($order->getOriginal('status'), ['confirmed', 'shipped', 'delivered'], true);
        if (! $reserve && ! $restore) {
            return;
        }
        $lines = $order->products()->orderBy('products.id')->get();
        if ($lines->isEmpty()) {
            throw ValidationException::withMessages(['status' => 'Commande sans produit.']);
        }
        foreach ($lines as $line) {
            $product = Product::query()->lockForUpdate()->findOrFail($line->id);
            $quantity = (int) $line->pivot->quantity;
            if ($quantity < 1 || ($reserve && $product->stock_quantity < $quantity)) {
                throw ValidationException::withMessages(['status' => 'Stock insuffisant ou quantité invalide.']);
            }
            $product->increment('stock_quantity', $reserve ? -$quantity : $quantity);
        }
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            OrderActivityLog::create([
                'order_id' => $order->id,
                'user_id' => Auth::id() ?? $order->user_id,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $order->status,
            ]);
        }
    }
}
