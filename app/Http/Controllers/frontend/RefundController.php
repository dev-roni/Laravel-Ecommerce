<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Refund;
use App\Http\Request\RefundRequest;

class RefundController extends Controller
{
    // Refund request form
    public function create(Order $order)
    {
        // নিজের order কিনা
        if ($order->user_id !== auth()->id()) abort(403);

        // Refund যোগ্য কিনা
        if (!in_array($order->status, ['delivered', 'cancelled'])) {
            return back()->with('error', 'শুধু delivered বা cancelled order-এ refund চাওয়া যাবে।');
        }

        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Unpaid order-এ refund প্রযোজ্য নয়।');
        }

        if ($order->hasActiveRefund()) {
            return back()->with('error', 'এই order-এ ইতিমধ্যে refund request আছে।');
        }

        return view('frontend.pages.refundCreate', compact('order'));
    }

    // Refund request submit
    public function store(RefundRequest $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $refundData = $request->validated();

        Refund::create([
            'order_id'       => $order->id,
            'user_id'        => auth()->id(),
            'amount'         => $refundData->amount,
            'reason'         => $refundData->reason,
            'refund_method'  => $refundData->refund_method,
            'refund_account' => $refundData->refund_account,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Refund request জমা হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।');
    }

     // Customer-এর সব refund
    public function index()
    {
        $refunds = Refund::where('user_id', auth()->id())
                         ->with(['order'])
                         ->latest()
                         ->paginate(10);

        return view('frontend.pages.refunds', compact('refunds'));
    }
}
