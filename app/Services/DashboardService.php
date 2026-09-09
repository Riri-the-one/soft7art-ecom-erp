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
            'topProducts' => Product::orderBy('created_at', 'desc')->take(5)->get(),
            'recentActivity' => Order::with('customer')->orderBy('created_at', 'desc')->take(5)->get(),
        ];
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
