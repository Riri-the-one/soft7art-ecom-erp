<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'photo_path', 'purchase_price', 'selling_price', 'stock_quantity'
    ];

    // Un produit peut figurer dans plusieurs commandes
    public function orders()
    {
        // On précise withPivot pour que Laravel récupère aussi la quantité et le prix unitaire
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'unit_price');
    }

    // Vérifie si le stock est critique (moins de 5 unités selon ton cahier des charges)
    public function isStockCritical(): bool
    {
        return $this->stock_quantity < 5;
    }

    // Met à jour le stock (ex: après une commande)
    public function updateStock(int $quantity): void
    {
        $this->stock_quantity += $quantity;
        $this->save();
    }
}
