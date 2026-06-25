<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderActivityLog extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'old_status', 'new_status'
    ];

    // Appartient à une commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Appartient à l'utilisateur qui a fait la modification
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}