<?php
// app/Models/Refund.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'amount', 'status',
        'reason', 'admin_note', 'refund_method',
        'refund_account', 'transaction_id', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'amount'      => 'decimal:2',
    ];

    const STATUS_LABELS = [
        'pending'   => ['label' => 'অপেক্ষমাণ',     'color' => 'warning'],
        'approved'  => ['label' => 'অনুমোদিত',      'color' => 'info'],
        'rejected'  => ['label' => 'প্রত্যাখ্যাত',  'color' => 'danger'],
        'completed' => ['label' => 'সম্পন্ন',        'color' => 'success'],
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['color'] ?? 'secondary';
    }
}