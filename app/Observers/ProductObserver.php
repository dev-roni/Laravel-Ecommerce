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
        // Stock পরিবর্তন
        if ($product->isDirty('stock')) {
            AuditService::log(
                'product.stock_updated',
                $product,
                ['stock' => $product->getOriginal('stock')],
                ['stock' => $product->stock]
            );
            return; // Stock change আলাদা log, general update-এ include করব না
        }

        // অন্য fields
        AuditService::logModelChange(
            'product.updated',
            $product,
            $this->tracked
        );
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
