<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des commandes.
     * Le Gestionnaire de Stock n'a pas vocation à voir les commandes clients,
     * son périmètre métier est le catalogue/stock.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('agent') || $user->hasRole('super_admin');
    }

    /**
     * Détermine si l'utilisateur peut consulter une commande précise.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->hasRole('agent') || $user->hasRole('super_admin');
    }

    /**
     * Détermine si l'utilisateur peut changer le statut d'une commande.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        return $user->hasRole('agent') || $user->hasRole('super_admin');
    }
}