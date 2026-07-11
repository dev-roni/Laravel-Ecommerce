<?php
// app/Services/RecentlyViewedService.php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class RecentlyViewedService
{
    private const KEY   = 'recently_viewed';
    private const LIMIT = 8; // সর্বোচ্চ কতটা রাখবে

    // Product দেখলে session-এ save করো
    public function add(int $productId): void
    {
        $ids = $this->getIds();

        // আগে থাকলে সরাও (সবার আগে আনতে)
        $ids = array_filter($ids, fn($id) => $id !== $productId);

        // সবার শুরুতে যোগ করো
        array_unshift($ids, $productId);

        // Limit ঠিক রাখো
        $ids = array_slice($ids, 0, self::LIMIT);

        session([self::KEY => array_values($ids)]);
    }

    // Session থেকে product IDs
    public function getIds(): array
    {
        return session(self::KEY, []);
    }

    // Products with details
    public function getProducts(?int $excludeId = null): Collection
    {
        $ids = $this->getIds();

        if ($excludeId) {
            $ids = array_filter($ids, fn($id) => $id !== $excludeId);
        }

        if (empty($ids)) {
            return collect();
        }

        // DB থেকে আনো, session-এর order বজায় রাখো
        $products = Product::with(['primaryImage', 'category', 'activeVariants'])
                           ->where('is_active', true)
                           ->whereIn('id', $ids)
                           ->get()
                           ->keyBy('id');

        // Session-এর order অনুযায়ী সাজাও
        return collect($ids)
            ->filter(fn($id) => $products->has($id))
            ->map(fn($id) => $products[$id])
            ->values();
    }

    // Clear
    public function clear(): void
    {
        session()->forget(self::KEY);
    }
}