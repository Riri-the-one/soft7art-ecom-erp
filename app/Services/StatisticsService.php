<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StatisticsService
{
    /**
     * Calcule les indicateurs du tableau de bord "Statistiques" pour une
     * période donnée ('all', 'today', 'week' ou 'month').
     */
    public function forFilter(string $filter): array
    {
        $orders = $this->baseQuery($filter)->get();

        $delivered = $orders->where('status', 'delivered');
        // Eager loading ciblé sur le seul sous-ensemble qui en a besoin (calcul
        // de marge), plutôt que sur la totalité des commandes récupérées.
        $delivered->load('products');

        $ordersByDate = $orders
            ->groupBy(fn ($order) => $order->created_at->format('Y-m-d'))
            ->sortKeys();

        return [
            'totalRevenue' => $delivered->sum('total_amount'),
            'totalOrders' => $orders->count(),
            'pendingOrders' => $orders->where('status', 'pending')->count(),
            'lowStockProducts' => Product::where('stock_quantity', '<', 10)->count(),
            'productMargin' => $this->computeProductMargin($delivered),
            'chartLabels' => $ordersByDate->keys()->values()->all(),
            'chartData' => $ordersByDate->map->count()->values()->all(),
        ];
    }

    /**
     * Une seule requête, filtrée par période, réutilisée pour tous les
     * indicateurs (au lieu de 4 requêtes quasi identiques).
     */
    private function baseQuery(string $filter)
    {
        $query = Order::query();

        return match ($filter) {
            'today' => $query->whereDate('created_at', Carbon::today()),
            'week' => $query->where('created_at', '>=', Carbon::now()->startOfWeek()),
            'month' => $query->where('created_at', '>=', Carbon::now()->startOfMonth()),
            default => $query,
        };
    }

    /**
     * Delivered product sales minus cost snapshots; excludes billed shipping and operating expenses.
     */
    private function computeProductMargin(Collection $deliveredOrders): float
    {
        $productMargin = 0;

        foreach ($deliveredOrders as $order) {
            $sellingTotal = 0;
            $purchaseTotal = 0;

            foreach ($order->products as $product) {
                $sellingTotal += $product->pivot->quantity * $product->pivot->unit_price;
                $purchaseTotal += $product->pivot->quantity * ($product->pivot->unit_cost ?? $product->purchase_price);
            }

            $productMargin += $sellingTotal - $purchaseTotal;
        }

        return round($productMargin, 2);
    }
}
