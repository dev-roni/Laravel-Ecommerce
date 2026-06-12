<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
    public function dashboard(){
        $stats = Cache::remember('admin:dashboard:stats', now()->addMinutes(10), function () {
            return [
                'total_products'  => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'low_stock'       => Product::where('has_variants', false)
                                            ->where('stock', '<=', 5)->count(),
                'total_orders'    => Order::count(),
                'pending_orders'  => Order::where('status', 'pending')->count(),
                'today_orders'    => Order::today()->count(),
                'today_revenue'   => Order::today()
                                        ->where('payment_status', 'paid')
                                        ->sum('total'),
                'total_revenue'   => Order::where('payment_status', 'paid')->sum('total'),
            ];
        });

        // সাম্প্রতিক orders
        $recentOrders = Order::with(['user', 'items'])
                         ->latest()
                         ->limit(8)
                         ->get();
        return view('backend.pages.dashboard', compact('stats','recentOrders'));
    }
}
