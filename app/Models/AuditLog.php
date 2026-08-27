<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'event',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip', 'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Event label বাংলায়
    const EVENT_LABELS = [
        // Order events
        'order.created'         => 'Order তৈরি',
        'order.status_changed'  => 'Order Status পরিবর্তন',
        'order.cancelled'       => 'Order বাতিল',
        'order.payment_updated' => 'Payment আপডেট',

        // Product events
        'product.created'       => 'Product তৈরি',
        'product.updated'       => 'Product আপডেট',
        'product.deleted'       => 'Product মুছে ফেলা',
        'product.stock_updated' => 'Stock আপডেট',

        // User events
        'user.login'            => 'Login',
        'user.logout'           => 'Logout',
        'user.registered'       => 'Registration',
        'user.banned'           => 'User ব্যান',
        'user.unbanned'         => 'User Unban',
        'user.password_changed' => 'Password পরিবর্তন',

        // Coupon events
        'coupon.created'        => 'Coupon তৈরি',
        'coupon.used'           => 'Coupon ব্যবহার',
        'coupon.deleted'        => 'Coupon মুছে ফেলা',

        // Refund events
        'refund.requested'      => 'Refund Request',
        'refund.approved'       => 'Refund অনুমোদন',
        'refund.rejected'       => 'Refund প্রত্যাখ্যান',
        'refund.completed'      => 'Refund সম্পন্ন',

        // Auth events
        'auth.login_failed'     => 'Login ব্যর্থ',
        'auth.google_login'     => 'Google Login',

        // Admin events
        'admin.category_created'=> 'Category তৈরি',
        'admin.category_deleted'=> 'Category মুছে ফেলা',
    ];

    public function getEventLabelAttribute(): string
    {
        return self::EVENT_LABELS[$this->event] ?? $this->event;
    }
}
