<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        return view('admin.refunds.index', compact('refunds', 'summary'));
    }
}
