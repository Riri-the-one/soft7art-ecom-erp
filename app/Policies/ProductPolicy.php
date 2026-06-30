<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Détermine si l'utilisateur peut voir le prix d'achat (la marge).
     * Donnée financière sensible : réservée au Gestionnaire de Stock et au Directeur.
     */
    public function viewPurchasePrice(User $user, ?Product $product = null): bool
    {
        return $user->hasRole('stock_manager') || $user->hasRole('super_admin');
    }

    /**
     * Détermine si l'utilisateur peut gérer le catalogue (créer/modifier).
     */
    public function manage(User $user): bool
    {
        return $user->hasRole('stock_manager') || $user->hasRole('super_admin');
    }
}