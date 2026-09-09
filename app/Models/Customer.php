<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'city', 'address', 'is_blacklisted'
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

    /**
     * Numéro de téléphone du client formaté pour un lien WhatsApp (wa.me),
     * sans le "+" (format attendu par l'API wa.me : indicatif pays + numéro).
     * Gère le format local marocain (0XXXXXXXXX) et les formats déjà
     * internationaux (+212XXXXXXXXX ou 212XXXXXXXXX).
     */
    public function getWhatsappNumberAttribute(): ?string
    {
        if (! $this->phone) {
            return null;
        }

        // On retire tout sauf les chiffres et un éventuel "+" en tête
        $digits = preg_replace('/[^0-9+]/', '', $this->phone);

        if ($digits === '' || $digits === '+') {
            return null;
        }

        if (str_starts_with($digits, '+')) {
            return substr($digits, 1);
        }

        if (str_starts_with($digits, '212')) {
            return $digits;
        }

        // Format local marocain : 0X XX XX XX XX -> on retire le 0 initial
        // et on préfixe l'indicatif international du Maroc.
        if (str_starts_with($digits, '0')) {
            return '212' . substr($digits, 1);
        }

        return $digits;
    }
}