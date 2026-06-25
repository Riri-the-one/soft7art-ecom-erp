<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. On vérifie si l'utilisateur est bien connecté (sinon, dehors !)
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // 2. Le Super Admin a un passe-partout, il passe toujours
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // 3. On vérifie si l'utilisateur a le rôle spécifique demandé pour cette page
        if ($user->hasRole($role)) {
            return $next($request);
        }

        // 4. S'il n'a ni le rôle demandé, ni le rôle admin, on le bloque (Erreur 403)
        abort(403, 'Accès non autorisé : Vous n\'avez pas les permissions nécessaires.');
    }
}