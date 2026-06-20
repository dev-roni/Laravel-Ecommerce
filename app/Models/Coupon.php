<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 
        'type', 
        'value', 
        'min_order_amount', 
        'max_discount',
        'usage_limit', 
        'used_count', 
        'per_user_limit',
        'starts_at', 
        'expires_at', 
        'is_active',
    ];

    protected $casts = [
        'starts_at'  => 'date',
        'expires_at' => 'date',
        'is_active'  => 'boolean',
        'value'      => 'decimal:2',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Coupon বৈধ কিনা চেক
    public function isValid(): array
    {
        if (!$this->is_active) {
            return [false, 'এই coupon সক্রিয় নেই।'];
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return [false, 'এই coupon এখনো শুরু হয়নি।'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return [false, 'এই coupon-এর মেয়াদ শেষ।'];
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return [false, 'এই coupon-এর ব্যবহার সীমা শেষ।'];
        }

        return [true, null];
    }

    // একজন user কতবার ব্যবহার করেছে
    public function userUsageCount(int $userId): int
    {
        return $this->usages()->where('user_id', $userId)->count();
    }

    // Discount amount হিসাব
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        }

        // percent
        $discount = ($subtotal * $this->value) / 100;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return round($discount, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
