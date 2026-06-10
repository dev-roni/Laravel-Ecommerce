<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    //একটিভ ভেরিয়েন্টগুলো পেতে
    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
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

    //ডিসকাউন্ট এর পারসেন্ট
    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->base_price > 0) {
            return (int) round(
                (($this->base_price - $this->sale_price) / $this->base_price) * 100
            );
        }
        return null;
    }

    // ══════════════════════════════
    // Cache Keys
    // ══════════════════════════════

    public static function cacheKey(string $type, mixed $id = null): string
    {
        return match($type) {
            'single'   => "product:{$id}",
            'featured' => 'products:featured',
            'latest'   => 'products:latest',
            'category' => "products:category:{$id}",
            'all'      => 'products:all',
            default    => "products:{$type}:{$id}",
        };
    }

    // ══════════════════════════════
    // Cache Methods
    // ══════════════════════════════

    // একটি product — সব details সহ
    public static function findWithCache(int $id): ?self
    {
        return Cache::remember(
            self::cacheKey('single', $id),
            now()->addHours(6),
            fn() => self::with([
                'category',
                'images',
                'activeVariants.attributeValues.attribute',
            ])->find($id)
        );
    }

    // Slug দিয়ে খোঁজা
    public static function findBySlugWithCache(string $slug): ?self
    {
        return Cache::remember(
            "product:slug:{$slug}",
            now()->addHours(6),
            fn() => self::with([
                'category',
                'images',
                'activeVariants.attributeValues.attribute',
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first()
        );
    }

    // Featured products
    public static function getFeatured(int $limit = 8): \Illuminate\Support\Collection
    {
        return Cache::remember(
            self::cacheKey('featured'),
            now()->addHours(3),
            fn() => self::with(['primaryImage', 'activeVariants'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->limit($limit)
                ->get()
        );
    }

    // Latest products
    public static function getLatest(int $limit = 12): \Illuminate\Support\Collection
    {
        return Cache::remember(
            self::cacheKey('latest'),
            now()->addHours(2),
            fn() => self::with(['primaryImage', 'activeVariants'])
                ->where('is_active', true)
                ->latest()
                ->limit($limit)
                ->get()
        );
    }

    // Category অনুযায়ী products
    public static function getByCategory(int $categoryId, int $limit = 20): \Illuminate\Support\Collection
    {
        return Cache::remember(
            self::cacheKey('category', $categoryId),
            now()->addHours(2),
            fn() => self::with(['primaryImage', 'activeVariants'])
                ->where('category_id', $categoryId)
                ->where('is_active', true)
                ->latest()
                ->limit($limit)
                ->get()
        );
    }

    // ══════════════════════════════
    // Cache Clear — data বদলালে
    // ══════════════════════════════

    public function clearCache(): void
    {
        Cache::forget(self::cacheKey('single', $this->id));
        Cache::forget("product:slug:{$this->slug}");
        Cache::forget(self::cacheKey('featured'));
        Cache::forget(self::cacheKey('latest'));
        Cache::forget(self::cacheKey('category', $this->category_id));
    }

    // ══════════════════════════════
    // Model Events — স্বয়ংক্রিয় cache clear
    // ══════════════════════════════

    protected static function booted(): void
    {
        // তৈরি, আপডেট বা মুছলে cache clear
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function (self $product) {
                $product->clearCache();
            });
        }
    }

    // ══════════════════════════════
    // Scopes — reusable query filters
    // ══════════════════════════════

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceBetween($query, float $min, float $max)
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->whereFullText(['name', 'short_description'], $term)
                     ->orWhere('sku', 'like', "%{$term}%");
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($q2) {
                // variant নেই, সরাসরি stock চেক
                $q2->where('has_variants', false)
                   ->where('stock', '>', 0);
            })->orWhere(function ($q2) {
                // variant আছে, variant-এর stock চেক
                $q2->where('has_variants', true)
                   ->whereHas('variants', fn($v) => $v->where('stock', '>', 0));
            });
        });
    }

    // ══════════════════════════════
    // for product review 
    // ══════════════════════════════

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)
                    ->where('is_approved', true)
                    ->latest();
    }

    // গড় rating
    public function getAvgRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    // মোট review সংখ্যা
    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }
}

