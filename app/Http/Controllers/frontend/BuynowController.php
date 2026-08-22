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

     // ── Order Place ───────────────────────────────
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => ['required','regex:/^(\+8801|8801|01)[3-9]\d{8}$/'],
            'shipping_address' => 'required|string|min:10|max:500',
            'shipping_city'    => 'required|string|min:3|max:50',
            'payment_method'   => 'required|in:cod,online',
            'notes'            => 'nullable|string|max:300',
            'idempotency_key'  => 'required|uuid',
        ], [
            'shipping_phone.regex' => 'সঠিক বাংলাদেশী মোবাইল নম্বর দিন।',
        ]);

        // Honeypot
        if ($request->filled('website')) {
            return redirect()->route('shop.index');
        }

        // Idempotency check
        $idempotency = app(\App\Services\IdempotencyService::class);
        $cached = $idempotency->check($request->idempotency_key, 'buy-now.order');
        if ($cached) {
            $order = Order::find($cached['body']['order_id']);
            if ($order) {
                return redirect()->route('orders.success', $order)
                                 ->with('info', 'Order আগেই তৈরি হয়েছে।');
            }
        }

        $buyNow = session('buy_now');

        if (!$buyNow) {
            return redirect()->route('shop.index')
                             ->with('error', 'Session expired। আবার চেষ্টা করুন।');
        }

        $product = Product::findOrFail($buyNow['product_id']);
        $variant = $buyNow['variant_id']
            ? ProductVariant::find($buyNow['variant_id'])
            : null;

        $quantity = $buyNow['quantity'];

        // Final stock check
        $stock = $variant ? $variant->stock : $product->stock;
        if ($stock < $quantity) {
            session()->forget('buy_now');
            return redirect()->route('shop.product', $product->slug)
                ->with('error', 'দুঃখিত, stock শেষ হয়ে গেছে।');
        }

        $unitPrice = $variant
            ? ($variant->sale_price ?? $variant->price)
            : ($product->sale_price  ?? $product->base_price);

        $subtotal = $unitPrice * $quantity;
        $shipping = $subtotal >= 1000 ? 0 : 60;
        $total    = $subtotal + $shipping;

        DB::beginTransaction();
        try {

            $order = Order::create([
                'user_id'          => auth()->id(),
                'order_number'     => Order::generateOrderNumber(),
                'shipping_name'    => $request->shipping_name,
                'shipping_phone'   => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'subtotal'         => $subtotal,
                'shipping_charge'  => $shipping,
                'discount'         => 0,
                'total'            => $total,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'unpaid',
                'status'           => 'pending',
                'notes'            => $request->notes,
            ]);

            $order->items()->create([
                'product_id'         => $product->id,
                'product_variant_id' => $variant?->id,
                'product_name'       => $product->name,
                'variant_label'      => $variant?->attributeValues
                                                  ->pluck('value')
                                                  ->join(' / '),
                'product_image'      => $product->primaryImage?->image,
                'quantity'           => $quantity,
                'unit_price'         => $unitPrice,
                'subtotal'           => $subtotal,
            ]);

            // Stock কমাও
            if ($variant) {
                $variant->decrement('stock', $quantity);
            } else {
                $product->decrement('stock', $quantity);
            }

            // Session clear
            session()->forget('buy_now');

            // Idempotency save
            $idempotency->store(
                $request->idempotency_key,
                'buy-now.order',
                200,
                ['order_id' => $order->id]
            );

            DB::commit();

            // Email
            Mail::to(auth()->user()->email)
                ->send(new OrderConfirmedMail($order));

            if ($request->payment_method === 'cod') {
                return redirect()->route('orders.success', $order)
                                 ->with('success', 'Order সফলভাবে হয়েছে!');
            }

            return redirect()->route('payment.pending', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}
