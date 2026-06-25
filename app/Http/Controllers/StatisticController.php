<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        // Chiffre d'affaires (livrées)
        $revenueQuery = Order::where('status', 'delivered');
        $revenueQuery->when($filter === 'today', fn($q) => $q->whereDate('created_at', Carbon::today()));
        $revenueQuery->when($filter === 'week', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfWeek()));
        $revenueQuery->when($filter === 'month', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfMonth()));
        $totalRevenue = $revenueQuery->sum('total_amount');

        // Total commandes
        $ordersQuery = Order::query();
        $ordersQuery->when($filter === 'today', fn($q) => $q->whereDate('created_at', Carbon::today()));
        $ordersQuery->when($filter === 'week', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfWeek()));
        $ordersQuery->when($filter === 'month', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfMonth()));
        $orders = $ordersQuery->get();
        $totalOrders = $orders->count();

        // Bénéfice net (commandes livrées)
        $netProfitQuery = Order::where('status', 'delivered');
        $netProfitQuery->when($filter === 'today', fn($q) => $q->whereDate('created_at', Carbon::today()));
        $netProfitQuery->when($filter === 'week', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfWeek()));
        $netProfitQuery->when($filter === 'month', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfMonth()));
        $deliveredOrders = $netProfitQuery->with('products')->get();

        $netProfit = 0;
        foreach ($deliveredOrders as $order) {
            $sellingTotal = 0;
            $purchaseTotal = 0;

            foreach ($order->products as $product) {
                $sellingTotal += $product->pivot->quantity * $product->pivot->unit_price;
                $purchaseTotal += $product->pivot->quantity * $product->purchase_price;
            }

            $netProfit += $sellingTotal - $purchaseTotal - $order->delivery_fee;
        }

        // Commandes en attente
        $pendingQuery = Order::where('status', 'pending');
        $pendingQuery->when($filter === 'today', fn($q) => $q->whereDate('created_at', Carbon::today()));
        $pendingQuery->when($filter === 'week', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfWeek()));
        $pendingQuery->when($filter === 'month', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfMonth()));
        $pendingOrders = $pendingQuery->count();

        // Produits en faible stock (global)
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();

        $ordersByDate = $orders->groupBy(fn ($order) => $order->created_at->format('Y-m-d'))->sortKeys();
        $chartLabels = $ordersByDate->keys()->values()->all();
        $chartData = $ordersByDate->map->count()->values()->all();

        return view('statistics.index', compact('totalRevenue', 'totalOrders', 'pendingOrders', 'lowStockProducts', 'netProfit', 'chartLabels', 'chartData', 'filter'));
    }
}