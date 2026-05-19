<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Order history
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->with('items')
                       ->latest()
                       ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    // Order details
    public function show(Order $order)
    {
        // শুধু নিজের order দেখতে পারবে
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'items.variant']);

        return view('shop.orders.show', compact('order'));
    }

    // Order success page
    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('shop.orders.success', compact('order'));
    }
}
