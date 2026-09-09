<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Assemble les données du tableau de bord pour l'utilisateur connecté,
     * en fonction de son rôle.
     */
    public function forUser(User $user): array
    {
        $data = $this->commonData();

        $data += match ($user->role) {
            'super_admin' => $this->superAdminData(),
            'agent' => $this->agentData($user),
            'stock_manager' => $this->stockManagerData(),
            default => [],
        };

        return $data;
    }

    /**
     * Indicateurs communs à tous les rôles.
     */
    private function commonData(): array
    {
        return [
            'totalClients' => Customer::count(),
            'ordersToday' => Order::whereDate('created_at', today())->count(),
            'lowStockProducts' => Product::where('stock_quantity', '<', 10)->count(),
        ];
    }

    /**
     * Indicateurs financiers et d'activité globale, réservés au Super Admin.
     */
    private function superAdminData(): array
    {
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        return [
            'monthlyGoal' => min(($monthlyRevenue / 50000) * 100, 100),
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $this->topProducts(),
            'recentActivity' => Order::with('customer')->orderBy('created_at', 'desc')->take(5)->get(),
            'agentLeaderboard' => $this->agentLeaderboard(),
        ];
    }

    /**
     * Les 5 produits les plus vendus, calculés sur les quantités réellement
     * commandées (table pivot order_product), hors commandes annulées ou
     * retournées.
     *
     * Auparavant ce widget affichait simplement les 5 produits les plus
     * récemment ajoutés au catalogue, ce qui ne correspondait pas à son
     * intitulé "Top 5 Produits".
     */
    private function topProducts()
    {
        $rows = DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->whereNotIn('orders.status', ['canceled', 'returned'])
            ->select('order_product.product_id', DB::raw('SUM(order_product.quantity) as units_sold'))
            ->groupBy('order_product.product_id')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        if ($rows->isEmpty()) {
            return collect();
        }

        // Une seule requête supplémentaire pour récupérer les produits concernés.
        $products = Product::whereIn('id', $rows->pluck('product_id'))->get()->keyBy('id');

        return $rows->map(function ($row) use ($products) {
            $product = $products->get($row->product_id);

            if (! $product) {
                return null;
            }

            $product->units_sold = (int) $row->units_sold;

            return $product;
        })->filter()->values();
    }

    /**
     * Classement des agents (Call Center) par nombre de commandes confirmées
     * ou livrées, pour le widget "Classement des agents" du dashboard.
     */
    private function agentLeaderboard()
    {
        return User::where('role', 'agent')
            ->withCount(['orders as score' => function ($query) {
                $query->whereIn('status', ['confirmed', 'delivered']);
            }])
            ->orderByDesc('score')
            ->take(5)
            ->get();
    }

    /**
     * Indicateurs de performance propres à l'agent connecté.
     */
    private function agentData(User $user): array
    {
        $agentOrders = Order::where('user_id', $user->id)->get();
        $totalAgentOrders = $agentOrders->count();

        $successRate = $totalAgentOrders > 0
            ? round($agentOrders->whereIn('status', ['delivered', 'confirmed'])->count() / $totalAgentOrders * 100, 1)
            : 0;

        return [
            'agentSuccessRate' => $successRate,
            'recentCustomers' => Customer::orderBy('created_at', 'desc')->take(4)->get(),
        ];
    }

    /**
     * Indicateurs de stock, réservés au Gestionnaire de Stock.
     */
    private function stockManagerData(): array
    {
        return [
            'criticalStock' => Product::where('stock_quantity', '<', 10)->get(),
            // Calcul fait en SQL plutôt qu'en chargeant tous les produits en
            // mémoire pour sommer côté PHP.
            'inventoryValue' => (float) Product::sum(DB::raw('purchase_price * stock_quantity')),
        ];
    }
}
