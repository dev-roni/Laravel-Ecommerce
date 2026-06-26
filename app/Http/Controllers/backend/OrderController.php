<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_name', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $summary = Cache::remember('admin:orders:summary', now()->addMinutes(5), fn() => [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'today'      => Order::today()->count(),
        ]);

        return view('backend.pages.order', compact('orders', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
       $order->load(['user', 'items.product', 'items.variant']);
        return view('backend.pages.orderShow', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

        // Status পরিবর্তন
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Delivered হলে সময় save করো
        $data = ['status' => $newStatus];
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $data['delivered_at'] = now();
            // Payment COD হলে paid করো
            if ($order->payment_method === 'cod') {
                $data['payment_status'] = 'paid';
            }
        }

        // Cancelled হলে stock ফেরত দাও
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $this->restoreStock($order);
        }

        $order->update($data);

        //notify customer if status change
        //mail sending for all status without 'pending'
        if($newStatus !== 'pending'){
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
        }

        // Cache clear
        Cache::forget('admin:orders:summary');
        Cache::forget('admin:dashboard:stats');

        return back()->with('success', 'Order status আপডেট হয়েছে।');
    }

        // Payment status পরিবর্তন
    public function updatePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Payment status আপডেট হয়েছে।');
    }

    // Stock ফেরত
    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $item->variant?->increment('stock', $item->quantity);
            } else {
                $item->product?->increment('stock', $item->quantity);
            }
        }
    }


    //invoice generate
    public function invoice(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
