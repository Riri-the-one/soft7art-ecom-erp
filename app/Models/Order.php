<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'user_id', 'status', 'delivery_fee', 'total_amount'
    ];

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

    // Possède un historique de modifications
    public function activityLogs()
    {
        return $this->hasMany(OrderActivityLog::class);
    }

    // --- LES MÉTHODES MÉTIER ---
    
    // Change le statut et crée automatiquement le log d'activité
    public function updateStatus(string $newStatus, int $userId): void
    {
        $oldStatus = $this->status;
        
        // 1. On met à jour la commande
        $this->status = $newStatus;
        $this->save();

        // 2. On crée la trace dans l'historique
        $this->activityLogs()->create([
            'user_id' => $userId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);
    }
}
