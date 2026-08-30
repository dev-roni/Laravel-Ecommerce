<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\AuditService;

class ProductObserver
{
    // Track করার fields
    private array $tracked = [
        'name', 'base_price', 'sale_price',
        'stock', 'is_active', 'category_id',
    ];
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
         AuditService::log(
            'product.created',
            $product,
            [],
            ['name' => $product->name, 'price' => $product->base_price]
        );
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
