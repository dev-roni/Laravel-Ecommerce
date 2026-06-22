<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function dashboard(){
         $stats = Cache::remember('admin:dashboard:stats', now()->addMinutes(5), function () {
            return [
                'today_revenue'   => Order::today()->where('payment_status','paid')->sum('total'),
                'today_orders'    => Order::today()->count(),
                'this_month_rev'  => Order::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)
                                          ->where('payment_status','paid')
                                          ->sum('total'),
                'total_revenue'   => Order::where('payment_status','paid')->sum('total'),
                'total_orders'    => Order::count(),
                'pending_orders'  => Order::where('status','pending')->count(),
                'total_customers' => User::where('role','customer')->count(),
                'total_products'  => Product::where('is_active',true)->count(),
                'low_stock'       => Product::where('has_variants',false)
                                            ->where('stock','<=',5)
                                            ->where('is_active',true)
                                            ->count(),
            ];
        });

        // ── Last 12 months revenue chart data ──────
        $revenueChart = $this->getRevenueChartData();

        // ── Order status breakdown ──────────────────
        $orderStatusData = $this->getOrderStatusData();

        // ── Top selling products ───────────────────
        $topProducts = $this->getTopProducts();

        // ── Recent orders ──────────────────────────
        $recentOrders = Order::with(['user'])
                             ->latest()
                             ->limit(8)
                             ->get();

        // ── Top customers ──────────────────────────
        $topCustomers = $this->getTopCustomers();

        return view('backend.pages.dashboard', compact(
            'stats',
            'revenueChart',
            'orderStatusData',
            'topProducts',
            'recentOrders',
            'topCustomers'
        ));
    }

    // ── Private helpers ────────────────────────────

    private function getRevenueChartData(): array
    {
        $data = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue, COUNT(*) as orders")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels'  => $data->pluck('month')->map(fn($m) =>
                \Carbon\Carbon::parse($m.'-01')->format('M Y')
            )->toArray(),
            'revenue' => $data->pluck('revenue')->map(fn($v) => round($v))->toArray(),
            'orders'  => $data->pluck('orders')->toArray(),
        ];
    }

    private function getOrderStatusData(): array
    {
        $data = Order::selectRaw('status, COUNT(*) as count')
                     ->groupBy('status')
                     ->pluck('count', 'status')
                     ->toArray();

        $labels = [
            'pending'    => 'অপেক্ষমাণ',
            'processing' => 'প্রক্রিয়াধীন',
            'shipped'    => 'পাঠানো',
            'delivered'  => 'পৌঁছেছে',
            'cancelled'  => 'বাতিল',
        ];

        return [
            'labels' => collect($data)->keys()->map(fn($k) => $labels[$k] ?? $k)->toArray(),
            'data'   => collect($data)->values()->toArray(),
            'colors' => ['#FACC15','#1DA1A8','#0A2540','#22C55E','#EF4444'],
        ];
    }

    private function getTopProducts(int $limit = 5): \Illuminate\Support\Collection
    {
        return OrderItem::join('orders','orders.id','=','order_items.order_id')
            ->where('orders.payment_status','paid')
            ->selectRaw('product_name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }

    private function getTopCustomers(int $limit = 5): \Illuminate\Support\Collection
    {
        return User::where('role','customer')
            ->withSum(['orders as total_spent' => fn($q) =>
                $q->where('payment_status','paid')
            ], 'total')
            ->withCount('orders')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();
    }

    // ── Sales Report Page ──────────────────────────
    public function salesReport(Request $request)
    {
        $period = $request->get('period', '30'); // 7, 30, 90, 365
        $from   = now()->subDays($period)->startOfDay();
        $to     = now()->endOfDay();

        if ($request->filled('from') && $request->filled('to')) {
            $from = \Carbon\Carbon::parse($request->from)->startOfDay();
            $to   = \Carbon\Carbon::parse($request->to)->endOfDay();
        }

        // Daily revenue in range
        $dailyRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Summary for period
        $summary = [
            'revenue'   => $dailyRevenue->sum('revenue'),
            'orders'    => Order::whereBetween('created_at', [$from, $to])->count(),
            'paid'      => Order::where('payment_status','paid')->whereBetween('created_at',[$from,$to])->count(),
            'cancelled' => Order::where('status','cancelled')->whereBetween('created_at',[$from,$to])->count(),
            'avg_order' => $dailyRevenue->count() > 0
                            ? $dailyRevenue->sum('revenue') / max($dailyRevenue->sum('orders'),1)
                            : 0,
        ];

        // Top products in period
        $topProducts = OrderItem::join('orders','orders.id','=','order_items.order_id')
            ->where('orders.payment_status','paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('product_name, SUM(quantity) as qty, SUM(subtotal) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Payment method breakdown
        $paymentBreakdown = Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        return view('admin.sales-report', compact(
            'dailyRevenue', 'summary', 'topProducts',
            'paymentBreakdown', 'period', 'from', 'to'
        ));
    }
}
