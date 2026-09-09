<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des clients (module CRM).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('agent') || $user->hasRole('super_admin');
    }

    /**
     * Détermine si l'utilisateur peut consulter la fiche d'un client précis.
     */
    public function view(User $user, Customer $customer): bool
    {
        return $user->hasRole('agent') || $user->hasRole('super_admin');
    }

    /**
     * Détermine si l'utilisateur peut créer un nouveau client.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('agent') || $user->hasRole('super_admin');
    }
}
