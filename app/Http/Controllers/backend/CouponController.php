<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Http\Requests\CouponRequest;
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
        $coupon = new Coupon();
        return view('backend.pages.couponCreate',compact('coupon'));
    }

    public function store(CouponRequest $request)
    {
        Coupon::create($request->validated());

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon create successfully');
    }

    public function edit(Coupon $coupon)
    {
        return view('backend.pages.couponCreate', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon updated successfully');
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
