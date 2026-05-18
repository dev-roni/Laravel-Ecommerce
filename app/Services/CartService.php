<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    // Cart-এর সব items
    public function items(): Collection
    {
        return CartItem::with([
            'product.primaryImage',
            'variant.attributeValues',
        ])
        ->where('user_id', Auth::id())
        ->get();
    }

    // Cart-এ কতটি item
    public function count(): int
    {
        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }

    // Subtotal
    public function subtotal(): float
    {
        return $this->items()->sum('subtotal');
    }

    // Shipping charge
    public function shippingCharge(): float
    {
        $subtotal = $this->subtotal();
        if ($subtotal >= 1000) return 0;    // ১০০০ টাকার বেশি হলে free
        return 60;                           // নাহলে ৬০ টাকা
    }

    // সর্বমোট
    public function total(): float
    {
        return $this->subtotal() + $this->shippingCharge();
    }

    // Cart-এ যোগ করা
    public function add(int $productId, ?int $variantId, int $quantity = 1): array
    {
        $product = Product::find($productId);

        if (!$product || !$product->is_active) {
            return ['success' => false, 'message' => 'Product পাওয়া যায়নি।'];
        }

        // Stock চেক
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || $variant->stock < $quantity) {
                return ['success' => false, 'message' => 'পর্যাপ্ত stock নেই।'];
            }
        } else {
            if ($product->stock < $quantity) {
                return ['success' => false, 'message' => 'পর্যাপ্ত stock নেই।'];
            }
        }

        // আগে থেকে cart-এ আছে কিনা
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->where('product_variant_id', $variantId)
                            ->first();

        if ($cartItem) {
            // আগে থেকে আছে — quantity বাড়াও
            $newQty = $cartItem->quantity + $quantity;
            $stock  = $variantId ? $variant->stock : $product->stock;

            if ($newQty > $stock) {
                return ['success' => false, 'message' => 'এত বেশি stock নেই।'];
            }

            $cartItem->update(['quantity' => $newQty]);
        } else {
            // নতুন item যোগ
            CartItem::create([
                'user_id'            => Auth::id(),
                'product_id'         => $productId,
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Cart-এ যোগ হয়েছে।',
            'count'   => $this->count(),
        ];
    }

    // Quantity আপডেট
    public function update(int $cartItemId, int $quantity): array
    {
        $cartItem = CartItem::where('id', $cartItemId)
                            ->where('user_id', Auth::id())
                            ->with(['product', 'variant'])
                            ->first();

        if (!$cartItem) {
            return ['success' => false, 'message' => 'Item পাওয়া যায়নি।'];
        }

        if ($quantity <= 0) {
            return $this->remove($cartItemId);
        }

        $stock = $cartItem->variant
            ? $cartItem->variant->stock
            : $cartItem->product->stock;

        if ($quantity > $stock) {
            return ['success' => false, 'message' => "সর্বোচ্চ {$stock}টি নেওয়া যাবে।"];
        }

        $cartItem->update(['quantity' => $quantity]);

        return [
            'success'  => true,
            'subtotal' => number_format($cartItem->subtotal),
            'total'    => number_format($this->total()),
            'count'    => $this->count(),
        ];
    }

    // Cart থেকে সরানো
    public function remove(int $cartItemId): array
    {
        CartItem::where('id', $cartItemId)
                ->where('user_id', Auth::id())
                ->delete();

        return [
            'success' => true,
            'message' => 'সরানো হয়েছে।',
            'count'   => $this->count(),
        ];
    }

    // Cart খালি করা
    public function clear(): void
    {
        CartItem::where('user_id', Auth::id())->delete();
    }

    // Stock validate করা (checkout-এর আগে)
    public function validateStock(): array
    {
        $errors = [];

        foreach ($this->items() as $item) {
            if (!$item->hasEnoughStock()) {
                $stock = $item->variant
                    ? $item->variant->stock
                    : $item->product->stock;
                $errors[] = "{$item->product->name}" .
                            ($item->variant ? " ({$item->variant->label})" : '') .
                            " — মাত্র {$stock}টি আছে।";
            }
        }

        return $errors;
    }
}