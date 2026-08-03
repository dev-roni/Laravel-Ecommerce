<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RefundApprovalRequest;
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

    public function update(RefundApprovalRequest $request, Refund $refund)
    {
        $validData = $request->validated();

        $data = [
            'status'         => $validData['status'],
            'admin_note'     => $validData['admin_note'],
            'transaction_id' => $validData['transaction_id'],
        ];

        if (in_array($validData['status'], ['approved', 'rejected', 'completed'])) {
            $data['resolved_at'] = now();
        }

        // Completed হলে order payment status refunded করো
        if ($validData['status'] === 'completed') {
            $refund->order->update(['payment_status' => 'refunded']);
        }

        $refund->update($data);

        // Customer-কে email পাঠাও
        Mail::to($refund->user->email)->send(new RefundStatusMail($refund));

        return back()->with('success', 'Refund status আপডেট হয়েছে।');
    }
}
