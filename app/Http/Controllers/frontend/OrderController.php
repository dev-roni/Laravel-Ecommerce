<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->with(['items'])
                       ->latest()
                       ->paginate(10);

        return view('shop.orders', compact('orders'));
    }


    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $order->load(['items.product', 'items.variant']);

        return view('frontend.pages.orderShow', compact('order'));
    }


    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        return view('frontend.pages.orderSuccess', compact('order'));
    }

    // Order বাতিল (শুধু pending অবস্থায়)
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'শুধু pending order বাতিল করা যাবে।');
        }

        // Stock ফেরত
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $item->variant?->increment('stock', $item->quantity);
            } else {
                $item->product?->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order বাতিল হয়েছে।');
    }
}
