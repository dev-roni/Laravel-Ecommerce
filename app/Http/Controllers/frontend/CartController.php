<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\CouponService;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CouponService $couponService) {}

    // Cart page
    public function index()
    {
        $items    = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shippingCharge();
        $total    = $this->cart->total();

        return view('frontend.pages.cart', compact('items', 'subtotal', 'shipping', 'total'));
    }

    // Cart-এ যোগ (AJAX)
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'integer|min:1|max:100',
        ]);

        $result = $this->cart->add(
            $request->product_id,
            $request->variant_id,
            $request->quantity ?? 1
        );

        if ($request->ajax() or $request->expectsJson()) {
            return response()->json($result);
        }

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    // Quantity আপডেট (AJAX)
    public function update(Request $request, int $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        $result = $this->cart->update($cartItemId, $request->quantity);

        return response()->json($result);
    }

    // Item সরানো (AJAX)
    public function remove(int $cartItemId)
    {
        $result = $this->cart->remove($cartItemId);

        if (request()->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    // Coupon apply (AJAX)
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $subtotal = $this->cart->subtotal();
        $result   = $this->couponService->apply($request->code, $subtotal);

        if ($result['success']) {
            // Session-এ coupon রাখো
            session([
                'coupon' => [
                    'id'       => $result['coupon_id'],
                    'code'     => $result['code'],
                    'discount' => $result['discount'],
                    'type'     => $result['type'],
                    'value'    => $result['value'],
                ],
            ]);
        }

        $shipping = $this->cart->shippingCharge();
        $discount = $result['success'] ? $result['discount'] : 0;
        $total    = $subtotal + $shipping - $discount;

        return response()->json([
            ...$result,
            'subtotal' => number_format($subtotal),
            'shipping' => number_format($shipping),
            'discount_fmt' => number_format($discount),
            'total'    => number_format($total),
        ]);
    }

    // Coupon সরানো (AJAX)
    public function removeCoupon()
    {
        session()->forget('coupon');

        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shippingCharge();
        $total    = $subtotal + $shipping;

        return response()->json([
            'success'  => true,
            'subtotal' => number_format($subtotal),
            'shipping' => number_format($shipping),
            'total'    => number_format($total),
        ]);
    }
}