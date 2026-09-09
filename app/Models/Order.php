<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'user_id', 'status', 'delivery_fee', 'total_amount',
    ];

    protected static function booted(): void
    {
        // Chaque commande reçoit un jeton unique dès sa création, utilisé pour
        // générer un lien de suivi public (voir Order::getTrackingUrlAttribute()).
        static::creating(function (Order $order) {
            if (empty($order->tracking_token)) {
                $order->tracking_token = Str::random(32);
            }
        });
    }

    /**
     * URL publique de suivi de commande (aucune authentification requise) :
     * à envoyer au client par WhatsApp ou SMS.
     *
     * Pour les commandes créées avant l'ajout de cette fonctionnalité (donc
     * sans token), on le génère et on le sauvegarde silencieusement ici,
     * au premier accès.
     */
    public function getTrackingUrlAttribute(): string
    {
        if (empty($this->tracking_token)) {
            $this->tracking_token = Str::random(32);
            $this->saveQuietly();
        }

        return route('orders.track', ['order' => $this->id, 'token' => $this->tracking_token]);
    }

    // Appartient à un client
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Appartient à un agent (utilisateur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Contient plusieurs produits (Table Pivot)
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'unit_price');
    }

    // Possède un historique des logs d'activité
    public function activityLogs()
    {
        return $this->hasMany(OrderActivityLog::class);
    }
}
