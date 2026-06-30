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

    
    // Possède un historique des logs d'activité
    public function activityLogs()
    {
        return $this->hasMany(OrderActivityLog::class);
    }
}