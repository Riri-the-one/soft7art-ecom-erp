<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'city', 'address', 'is_blacklisted',
    ];

    // Un client peut passer plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getDeliveryRateAttribute(): int
    {
        // On réutilise la relation 'orders' si elle est déjà chargée (eager loading
        // fait par CustomerController::index()/show()) pour éviter 2 requêtes
        // supplémentaires par client affiché (problème N+1).
        $orders = $this->relationLoaded('orders') ? $this->orders : $this->orders()->get();

        $total = $orders->count();
        if ($total === 0) {
            return 0;
        }

        $delivered = $orders->where('status', 'delivered')->count();

        return (int) round(($delivered / $total) * 100);
    }
}
