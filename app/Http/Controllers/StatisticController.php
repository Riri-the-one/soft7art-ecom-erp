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
        $totalOrders = $ordersQuery->count();

        // Commandes en attente
        $pendingQuery = Order::where('status', 'pending');
        $pendingQuery->when($filter === 'today', fn($q) => $q->whereDate('created_at', Carbon::today()));
        $pendingQuery->when($filter === 'week', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfWeek()));
        $pendingQuery->when($filter === 'month', fn($q) => $q->where('created_at', '>=', Carbon::now()->startOfMonth()));
        $pendingOrders = $pendingQuery->count();

        // Produits en faible stock (global)
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();

        return view('statistics.index', compact('totalRevenue', 'totalOrders', 'pendingOrders', 'lowStockProducts', 'filter'));
    }
}