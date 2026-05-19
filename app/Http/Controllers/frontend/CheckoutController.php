<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                             ->with('error', 'Cart খালি আছে।');
        }

        // Stock validate
        $stockErrors = $this->cart->validateStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                             ->with('stock_errors', $stockErrors);
        }

        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shippingCharge();
        $total    = $this->cart->total();
        $user     = auth()->user();

        return view('shop.checkout',
            compact('items', 'subtotal', 'shipping', 'total', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city'    => 'required|string|max:100',
            'payment_method'   => 'required|in:cod,bkash,nagad,card',
            'notes'            => 'nullable|string|max:300',
        ], [
            'shipping_name.required'    => 'নাম দিতে হবে।',
            'shipping_phone.required'   => 'ফোন নম্বর দিতে হবে।',
            'shipping_address.required' => 'ঠিকানা দিতে হবে।',
            'shipping_city.required'    => 'শহর দিতে হবে।',
            'payment_method.required'   => 'Payment পদ্ধতি নির্বাচন করুন।',
        ]);

        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                             ->with('error', 'Cart খালি আছে।');
        }

        // Stock চেক
        $stockErrors = $this->cart->validateStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                             ->with('stock_errors', $stockErrors);
        }

        DB::beginTransaction();
        try {
            $subtotal = $this->cart->subtotal();
            $shipping = $this->cart->shippingCharge();

            // Order তৈরি
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
                'total'            => $subtotal + $shipping,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'unpaid',
                'status'           => 'pending',
                'notes'            => $request->notes,
            ]);

            // Order items তৈরি + stock কমানো
            foreach ($items as $item) {
                $order->items()->create([
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->product->name,
                    'variant_label'      => $item->variant?->label,
                    'product_image'      => $item->product->primaryImage?->image,
                    'quantity'           => $item->quantity,
                    'unit_price'         => $item->price,
                    'subtotal'           => $item->subtotal,
                ]);

                // Stock কমাও
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Cart খালি করো
            $this->cart->clear();

            DB::commit();

            return redirect()->route('orders.success', $order)
                             ->with('success', 'Order সফলভাবে হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}