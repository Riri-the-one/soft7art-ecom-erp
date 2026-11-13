<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'photo_path', 'purchase_price', 'selling_price', 'stock_quantity', 'shelf_location',
    ];

    protected function casts(): array
    {
        return ['purchase_price' => 'decimal:2', 'selling_price' => 'decimal:2', 'stock_quantity' => 'integer'];
    }

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            if ($product->orders()->exists()) {
                throw ValidationException::withMessages(['product' => 'Produit associé ? un historique de commandes.']);
            }
        });
    }

    // Un produit peut figurer dans plusieurs commandes
    public function orders()
    {
        // On précise withPivot pour que Laravel récupère aussi la quantité et le prix unitaire
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'unit_price');
    }

    /**
     * Code utilisé pour générer le code-barres du produit (imprimable sur
     * l'étiquette de rayon). Basé sur l'ID pour garantir l'unicité.
     */
    public function getBarcodeValueAttribute(): string
    {
        return 'PRD'.str_pad((string) $this->id, 8, '0', STR_PAD_LEFT);
    }

    // Vérifie si le stock est critique (moins de 5 unités selon ton cahier des charges)
    public function isStockCritical(): bool
    {
        return $this->stock_quantity < 5;
    }

    // Met à jour le stock (ex: après une commande)
    public function updateStock(int $quantity): void
    {
        $query = static::whereKey($this->getKey());
        if ($quantity < 0) {
            $query->where('stock_quantity', '>=', -$quantity);
        }
        if ($query->increment('stock_quantity', $quantity) !== 1) {
            throw new InvalidArgumentException('Stock cannot be negative or product is missing.');
        }
        $this->refresh();
    }
}
