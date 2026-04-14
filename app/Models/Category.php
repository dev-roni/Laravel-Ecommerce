<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\ProductCategoryFactory> */
    use HasFactory;

    protected $table='categories';
    
    protected $fillable=[
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'level',
        'order',
        'is_active'
    ];

    // সরাসরি parent
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // সরাসরি children
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    // নতুন category যোগ করলে order স্বয়ংক্রিয় ঠিক হবে
    public static function nextOrder($parent_id): int
    {
        return static::where('parent_id', $parent_id)->max('order') + 1;
    }

    // children-এর children সহ সব নিচের স্তর (recursive)
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // এই category-র সরাসরি products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // এই category + সব child category-র products একসাথে
    public function allProducts()
    {
        $categoryIds = $this->getAllChildIds();
        $categoryIds[] = $this->id;
        return Product::whereIn('category_id', $categoryIds);
    }

    // সব child-এর id বের করার helper
    private function getAllChildIds(): array
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllChildIds());
        }
        return $ids;
    }

    // পুরো path দেখাবে: Electronics > Mobile > Samsung
    public function getBreadcrumbAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        return implode(' > ', $path);
    }
}
