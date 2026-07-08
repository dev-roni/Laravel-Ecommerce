<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundStatusMail;

use App\Models\Refund;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::with(['order', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $refunds = $query->paginate(20)->withQueryString();

        $summary = [
            'pending'   => Refund::where('status', 'pending')->count(),
            'approved'  => Refund::where('status', 'approved')->count(),
            'completed' => Refund::where('status', 'completed')->count(),
            'total_amt' => Refund::where('status', 'completed')->sum('amount'),
        ];

        return view('backend.pages.refunds', compact('refunds', 'summary'));
    }

    public function show(Refund $refund)
    {
        $refund->load(['order.items', 'user']);
        return view('backend.pages.refundShow', compact('refund'));
    }

    public function update(Request $request, Refund $refund)
    {
        $request->validate([
            'status'         => 'required|in:approved,rejected,completed',
            'admin_note'     => 'nullable|string|max:500',
            'transaction_id' => 'required_if:status,completed|nullable|string',
        ], [
            'transaction_id.required_if' => 'Completed করতে Transaction ID দিতে হবে।',
        ]);

        $data = [
            'status'         => $request->status,
            'admin_note'     => $request->admin_note,
            'transaction_id' => $request->transaction_id,
        ];

        if (in_array($request->status, ['approved', 'rejected', 'completed'])) {
            $data['resolved_at'] = now();
        }

        // Completed হলে order payment status refunded করো
        if ($request->status === 'completed') {
            $refund->order->update(['payment_status' => 'refunded']);
        }

        $refund->update($data);

        // Customer-কে email পাঠাও
        Mail::to($refund->user->email)->send(new RefundStatusMail($refund));

        return back()->with('success', 'Refund status আপডেট হয়েছে।');
    }
}
