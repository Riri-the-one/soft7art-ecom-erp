<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $data = [];

        // Common data for all roles
        $data['totalClients'] = Customer::count();
        $data['ordersToday'] = Order::whereDate('created_at', today())->count();
        $data['lowStockProducts'] = Product::where('stock_quantity', '<', 10)->count();

        // Role-specific data
        if ($user->role === 'super_admin') {
            // Monthly goal calculation (50,000 DH target)
            $monthlyRevenue = Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');
            $data['monthlyGoal'] = min(($monthlyRevenue / 50000) * 100, 100);
            $data['monthlyRevenue'] = $monthlyRevenue;

            // Top 5 products (most recently added as fallback)
            $data['topProducts'] = Product::orderBy('created_at', 'desc')->take(5)->get();

            // Recent activity (last 5 orders)
            $data['recentActivity'] = Order::with('customer')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        if ($user->role === 'agent') {
            // Agent success rate calculation
            $agentOrders = Order::where('user_id', $user->id)->get();
            $totalAgentOrders = $agentOrders->count();
            
            if ($totalAgentOrders > 0) {
                $successfulOrders = $agentOrders->whereIn('status', ['delivered', 'confirmed'])->count();
                $data['agentSuccessRate'] = round(($successfulOrders / $totalAgentOrders) * 100, 1);
            } else {
                $data['agentSuccessRate'] = 0;
            }

            // Recent customers (last 4)
            $data['recentCustomers'] = Customer::orderBy('created_at', 'desc')->take(4)->get();
        }

        if ($user->role === 'stock_manager') {
            // Critical stock products
            $data['criticalStock'] = Product::where('stock_quantity', '<', 10)->get();

            // Inventory value calculation
            $data['inventoryValue'] = Product::get()->sum(function ($product) {
                return $product->purchase_price * $product->stock_quantity;
            });
        }

        return view('dashboard', $data);
    }
}
