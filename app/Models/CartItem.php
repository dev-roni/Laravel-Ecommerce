<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'product_variant_id', 'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // এই item-এর দাম
    public function getPriceAttribute(): float
    {
        if ($this->variant) {
            return (float) ($this->variant->sale_price ?? $this->variant->price);
        }
        return (float) ($this->product->sale_price ?? $this->product->base_price);
    }

    // এই item-এর subtotal
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    // Stock যথেষ্ট আছে কিনা
    public function hasEnoughStock(): bool
    {
        $stock = $this->variant ? $this->variant->stock : $this->product->stock;
        return $stock >= $this->quantity;
    }
}
