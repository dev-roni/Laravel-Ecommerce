<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable=[
        'category_id', 
        'name', 
        'slug', 
        'short_description', 
        'description',
        'brand', 
        'sku', 
        'base_price', 
        'sale_price', 
        'stock',
        'has_variants', 
        'is_active', 
        'is_featured', 
        'weight',
    ];

    protected $casts = [
        'has_variants' => 'boolean',
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'base_price'   => 'decimal:2',
        'sale_price'   => 'decimal:2',
    ];

    //কোন ক্যাটাগরি থেকে এসেছে খুজে পেতে
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //এই প্রডাক্টের কোন কোন ইমেজ আছে (ভেরিয়েন্টের)
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    //প্রধান ছবি
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // কি কি বিকল্প আছে
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // বর্তমান দাম (sale থাকলে sale, না থাকলে base)
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->base_price;
    }

    // মোট stock (variant থাকলে সব variant-এর stock যোগ)
    public function getTotalStockAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->sum('stock');
        }
        return $this->stock;
    }
}

