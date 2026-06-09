<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

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
}