<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('usages')
                         ->latest()
                         ->paginate(20);

        return view('backend.pages.coupons', compact('coupons'));
    }

    public function create()
    {
        return view('backend.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'              => 'required|string|max:30|unique:coupons,code',
            'type'              => 'required|in:fixed,percent',
            'value'             => 'required|numeric|min:0',
            'min_order_amount'  => 'nullable|numeric|min:0',
            'max_discount'      => 'nullable|numeric|min:0',
            'usage_limit'       => 'nullable|integer|min:1',
            'per_user_limit'    => 'required|integer|min:1',
            'starts_at'         => 'nullable|date',
            'expires_at'        => 'nullable|date|after_or_equal:starts_at',
        ], [
            'code.required'  => 'Coupon code দিতে হবে।',
            'code.unique'    => 'এই code আগে থেকেই আছে।',
            'value.required' => 'মূল্য দিতে হবে।',
        ]);

        Coupon::create([
            ...$request->all(),
            'code'      => strtoupper($request->code),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon তৈরি হয়েছে।');
    }

    public function edit(Coupon $coupon)
    {
        return view('backend.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'              => 'required|string|max:30|unique:coupons,code,' . $coupon->id,
            'type'              => 'required|in:fixed,percent',
            'value'             => 'required|numeric|min:0',
            'min_order_amount'  => 'nullable|numeric|min:0',
            'max_discount'      => 'nullable|numeric|min:0',
            'usage_limit'       => 'nullable|integer|min:1',
            'per_user_limit'    => 'required|integer|min:1',
            'starts_at'         => 'nullable|date',
            'expires_at'        => 'nullable|date|after_or_equal:starts_at',
        ]);

        $coupon->update([
            ...$request->all(),
            'code'      => strtoupper($request->code),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon আপডেট হয়েছে।');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon মুছে ফেলা হয়েছে।');
    }

    public function toggleActive(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'অবস্থা পরিবর্তন হয়েছে।');
    }
}
