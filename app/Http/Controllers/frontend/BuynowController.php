<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;

class BuynowController extends Controller
{
    // ── Session-এ save ────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'পণ্যটি পাওয়া যাচ্ছে না।',
            ]);
        }

        // Stock check
        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            if ($variant->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "পর্যাপ্ত stock নেই। মাত্র {$variant->stock}টি আছে।",
                ]);
            }
        } else {
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "পর্যাপ্ত stock নেই। মাত্র {$product->stock}টি আছে।",
                ]);
            }
        }

        // Session-এ save
        session([
            'buy_now' => [
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity'   => $request->quantity,
            ],
        ]);

        return response()->json([
            'success'      => true,
            'redirect_url' => route('buy-now.checkout'),
        ]);
    }

    // ── Checkout Page ─────────────────────────────
    public function checkout()
    {
        $buyNow = session('buy_now');

        if (!$buyNow) {
            return redirect()->route('shop.index')
                             ->with('error', 'Session expired। আবার চেষ্টা করুন।');
        }

        $product = Product::with(['primaryImage', 'category'])
                          ->findOrFail($buyNow['product_id']);

        $variant = $buyNow['variant_id']
            ? ProductVariant::with('attributeValues')->find($buyNow['variant_id'])
            : null;

        $quantity = $buyNow['quantity'];

        // Price calculate
        $unitPrice = $variant
            ? ($variant->sale_price ?? $variant->price)
            : ($product->sale_price  ?? $product->base_price);

        $subtotal = $unitPrice * $quantity;
        $shipping = $subtotal >= 1000 ? 0 : 60;
        $total    = $subtotal + $shipping;

        $user = auth()->user();

        return view('shop.buy-now.checkout', compact(
            'product', 'variant', 'quantity',
            'unitPrice', 'subtotal', 'shipping', 'total',
            'user'
        ));
    }
}
