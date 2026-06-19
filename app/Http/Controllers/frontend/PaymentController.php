<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Mail\OrderConfirmedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class PaymentController extends Controller
{
    public function pending(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order);
        }

        return view('frontend.pages.paymentredirect', compact('order'));
    }
    // ── Payment শুরু করো ──────────────────────────────────
    public function initiate(Order $order)
    {
        // নিজের order কিনা চেক
        if ($order->user_id !== auth()->id()) abort(403);

        // আগেই paid হলে redirect
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                             ->with('info', 'এই order ইতিমধ্যে পরিশোধিত।');
        }

        $user = auth()->user();

        try {
            $response = Sslcommerz::setOrder(
                    amount:      $order->total,
                    invoiceId:   $order->order_number,
                    productName: "Order #{$order->order_number}"
                )
                ->setCustomer(
                    name:  $order->shipping_name,
                    email: $user->email,
                    phone: $order->shipping_phone
                )
                ->setShippingInfo(
                    $order->items->count(),
                     $order->shipping_address
                )
                ->makePayment();
               
            if ($response->success()) {
                // Order-এ transaction id save করো
                $order->update([
                    'ssl_transaction_id' => $order->order_number,
                ]);

                // SSLCommerz payment page-এ redirect
                return redirect($response->gatewayPageURL());
            }

            return back()->with('error', 'Payment শুরু করা যায়নি। আবার চেষ্টা করুন।');

        } catch (\Exception $e) {
            Log::error('SSLCommerz initiate error: ' . $e->getMessage());
            return back()->with('error', 'Payment সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    // ── Payment সফল ──────────────────────────────────────
    public function success(Request $request)
    {

        $order = Order::where('order_number', $request->tran_id)->first();

        if (!$order) {
            return redirect()->route('shop.index')
                             ->with('error', 'Order খুঁজে পাওয়া যায়নি।');
        }

        // Validate payment
        $isValid = Sslcommerz::validatePayment(
            $request->all(),
            $request->tran_id,
            $order->total
        );

        if ($isValid) {
            DB::beginTransaction();
            try {
                $order->update([
                    'payment_status'     => 'paid',
                    'status'             => 'processing',
                    'ssl_val_id'         => $request->val_id,
                    'ssl_transaction_id' => $request->tran_id,
                    'ssl_response'       => json_encode($request->all()),
                ]);

                DB::commit();

                // User session restore
                Auth::loginUsingId($order->user_id);
                // Session regenerate (security)
                request()->session()->regenerate();

                //send order confirm mail
                Mail::to($order->user->email)->send(new OrderConfirmedMail($order));

                return redirect()
                    ->route('orders.success', $order)
                    ->with('success', 'Payment সফল হয়েছে! আপনার order confirm হয়েছে।');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('SSLCommerz success save error: ' . $e->getMessage());
            }
        }

        // Validation fail
        $order->update(['payment_status' => 'unpaid']);

        return redirect()
            ->route('orders.show', $order)
            ->with('error', 'Payment validation ব্যর্থ হয়েছে।');
    }

    // ── Payment ব্যর্থ ─────────────────────────────────────
    public function fail(Request $request)
    {
        $order = Order::where('order_number', $request->tran_id)->first();

        if ($order) {
            $order->update([
                'payment_status' => 'unpaid',
                'ssl_response'   => json_encode($request->all()),
            ]);
        }

        Auth::loginUsingId($order->user_id);
        request()->session()->regenerate();

        return redirect()
            ->route($order ? 'orders.show' : 'shop.index', $order ?? [])
            ->with('error', 'Payment ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
    }

    // ── Payment বাতিল ─────────────────────────────────────
    public function cancel(Request $request)
    {
        $order = Order::where('order_number', $request->tran_id)->first();

        Auth::loginUsingId($order->user_id);
        request()->session()->regenerate();
        
        return redirect()
            ->route($order ? 'orders.show' : 'shop.index', $order ?? [])
            ->with('error', 'Payment বাতিল করা হয়েছে।');
    }

    // ── IPN (Instant Payment Notification) ────────────────
    // SSLCommerz নিজে থেকে এই route-এ POST করে
    public function ipn(Request $request)
    {
        $order = Order::where('order_number', $request->tran_id)->first();

        if (!$order) return response('Order not found', 404);

        $isValid = Sslcommerz::validatePayment(
            $request->all(),
            $request->tran_id,
            $order->total
        );

        if ($isValid && $request->status === 'VALID') {
            $order->update([
                'payment_status'     => 'paid',
                'status'             => 'processing',
                'ssl_val_id'         => $request->val_id,
                'ssl_response'       => json_encode($request->all()),
            ]);

            // User session restore
            Auth::loginUsingId($order->user_id);
            // Session regenerate (security)
            request()->session()->regenerate();

            Log::info("IPN: Order #{$order->order_number} payment confirmed.");
        }

        return response('IPN received', 200);
    }
}