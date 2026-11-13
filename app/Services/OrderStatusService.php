<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderStatusService
{
    public const TRANSITIONS = [
        'pending' => ['confirmed', 'canceled'],
        'confirmed' => ['shipped', 'canceled'],
        'shipped' => ['delivered', 'returned'],
        'delivered' => ['returned'],
        'returned' => [],
        'canceled' => [],
    ];

    public function transition(Order $order, string $status): bool
    {
        return DB::transaction(function () use ($order, $status) {
            $locked = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($locked->status === $status) {
                $order->setRawAttributes($locked->getAttributes(), true);

                return false;
            }
            if (! in_array($status, self::TRANSITIONS[$locked->status] ?? [], true)) {
                throw ValidationException::withMessages(['status' => 'Transition de statut non autorisée.']);
            }
            $locked->update(['status' => $status]);
            $order->setRawAttributes($locked->getAttributes(), true);

            return true;
        }, 3);
    }
}
