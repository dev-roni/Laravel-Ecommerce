<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Refund;

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
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $request->validate([
            'reason'         => 'required|string|min:20|max:500',
            'refund_method'  => 'required|in:bkash,nagad,bank',
            'refund_account' => 'required|string|max:50',
            'amount'         => 'required|numeric|min:1|max:' . $order->total,
        ], [
            'reason.required'         => 'কারণ লিখতে হবে।',
            'reason.min'              => 'কারণ কমপক্ষে ২০ অক্ষর হতে হবে।',
            'refund_method.required'  => 'Refund পদ্ধতি নির্বাচন করুন।',
            'refund_account.required' => 'Account নম্বর দিতে হবে।',
            'amount.max'              => 'Refund amount order total-এর বেশি হতে পারবে না।',
        ]);

        Refund::create([
            'order_id'       => $order->id,
            'user_id'        => auth()->id(),
            'amount'         => $request->amount,
            'reason'         => $request->reason,
            'refund_method'  => $request->refund_method,
            'refund_account' => $request->refund_account,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Refund request জমা হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।');
    }
}
