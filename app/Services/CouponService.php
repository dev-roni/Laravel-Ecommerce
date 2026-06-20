<?php
// app/Services/CouponService.php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    // Coupon apply করার চেষ্টা
    public function apply(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'এই coupon code সঠিক নয়।'];
        }

        [$isValid, $error] = $coupon->isValid();
        if (!$isValid) {
            return ['success' => false, 'message' => $error];
        }

        // ন্যূনতম order amount চেক
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return [
                'success' => false,
                'message' => "এই coupon ব্যবহার করতে ন্যূনতম ৳" .
                             number_format($coupon->min_order_amount) . " কিনতে হবে।",
            ];
        }

        // Per-user limit চেক
        if (Auth::check()) {
            $usedByUser = $coupon->userUsageCount(Auth::id());
            if ($usedByUser >= $coupon->per_user_limit) {
                return ['success' => false, 'message' => 'আপনি এই coupon-এর সীমা ব্যবহার করে ফেলেছেন।'];
            }
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'success'  => true,
            'message'  => 'Coupon সফলভাবে apply হয়েছে!',
            'coupon_id'=> $coupon->id,
            'code'     => $coupon->code,
            'discount' => $discount,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
        ];
    }

    // Order confirm হলে usage save করা
    public function recordUsage(int $couponId, int $userId, int $orderId, float $discount): void
    {
        \App\Models\CouponUsage::create([
            'coupon_id'       => $couponId,
            'user_id'         => $userId,
            'order_id'        => $orderId,
            'discount_amount' => $discount,
        ]);

        Coupon::where('id', $couponId)->increment('used_count');
    }
}