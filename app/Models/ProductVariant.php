<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 
        'sku', 
        'price', 
        'sale_price', 
        'stock', 
        'image', 
        'is_active'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //মেনি টু মেনি রিলেশন
    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'variant_values',
            'product_variant_id',
            'attribute_value_id'
        );
    }

    // variant-এর label: "Red / XL"
    public function getLabelAttribute()
    {
        return $this->attributeValues->pluck('value')->join(' / ');
    }
}
