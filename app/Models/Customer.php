<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'city', 'address', 'is_blacklisted'
    ];

    // Un client peut passer plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Méthode pour calculer le taux de livraison (à implémenter plus tard avec de vraies données)
    public function getDeliveryRate(): float
    {
        $totalOrders = $this->orders()->count();
        if ($totalOrders === 0) return 0.0;

        $deliveredOrders = $this->orders()->where('status', 'delivered')->count();
        return ($deliveredOrders / $totalOrders) * 100;
    }
}