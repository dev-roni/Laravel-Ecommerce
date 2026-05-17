<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number',
        'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city',
        'subtotal', 'shipping_charge', 'discount', 'total',
        'status', 'payment_method', 'payment_status', 'transaction_id', 'notes',
        'delivered_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'subtotal'     => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    // Status বাংলায়
    const STATUS_LABELS = [
        'pending'    => ['label' => 'অপেক্ষমাণ',  'color' => 'warning'],
        'processing' => ['label' => 'প্রক্রিয়াধীন', 'color' => 'info'],
        'shipped'    => ['label' => 'পাঠানো হয়েছে', 'color' => 'primary'],
        'delivered'  => ['label' => 'পৌঁছেছে',     'color' => 'success'],
        'cancelled'  => ['label' => 'বাতিল',        'color' => 'danger'],
    ];

    const PAYMENT_LABELS = [
        'unpaid'   => ['label' => 'অপরিশোধিত', 'color' => 'danger'],
        'paid'     => ['label' => 'পরিশোধিত',  'color' => 'success'],
        'refunded' => ['label' => 'ফেরত',       'color' => 'secondary'],
    ];

    const PAYMENT_METHOD_LABELS = [
        'cod'   => 'ক্যাশ অন ডেলিভারি',
        'bkash' => 'bKash',
        'nagad' => 'Nagad',
        'card'  => 'Card',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['color'] ?? 'secondary';
    }

    public function getPaymentLabelAttribute(): string
    {
        return self::PAYMENT_LABELS[$this->payment_status]['label'] ?? $this->payment_status;
    }

    public function getPaymentColorAttribute(): string
    {
        return self::PAYMENT_LABELS[$this->payment_status]['color'] ?? 'secondary';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHOD_LABELS[$this->payment_method] ?? $this->payment_method;
    }

    // Order number তৈরি
    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    // Scopes
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}