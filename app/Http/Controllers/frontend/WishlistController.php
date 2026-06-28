<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    // Wishlist page
    public function index()
    {
        $items = Wishlist::where('user_id', auth()->id())
                         ->with(['product.primaryImage',
                                 'product.category',
                                 'product.activeVariants'])
                         ->latest()
                         ->get();

        return view('shop.wishlist', compact('items'));
    }

    // Toggle — add বা remove (AJAX)
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', auth()->id())
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($existing) {
            $existing->delete();
            $wished = false;
            $message = 'Wishlist থেকে সরানো হয়েছে।';
        } else {
            Wishlist::create([
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
            ]);
            $wished  = true;
            $message = 'Wishlist-এ যোগ হয়েছে!';
        }

        return response()->json([
            'success' => true,
            'wished'  => $wished,
            'message' => $message,
            'count'   => Wishlist::where('user_id', auth()->id())->count(),
        ]);
    }

    // একটি item সরানো
    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) abort(403);

        $wishlist->delete();

        return back()->with('success', 'Wishlist থেকে সরানো হয়েছে।');
    }

    // Wishlist থেকে সরাসরি Cart-এ
    public function moveToCart(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) abort(403);

        $result = app(\App\Services\CartService::class)
                    ->add($wishlist->product_id, null, 1);

        if ($result['success']) {
            $wishlist->delete(); // wishlist থেকে সরাও
        }

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    // সব wishlist Cart-এ
    public function moveAllToCart()
    {
        $cartService = app(\App\Services\CartService::class);
        $items       = Wishlist::where('user_id', auth()->id())
                               ->with('product')
                               ->get();

        $added   = 0;
        $skipped = 0;

        foreach ($items as $item) {
            if ($item->product->total_stock > 0) {
                $result = $cartService->add($item->product_id, null, 1);
                if ($result['success']) {
                    $item->delete();
                    $added++;
                }
            } else {
                $skipped++;
            }
        }

        $msg = "{$added}টি পণ্য Cart-এ যোগ হয়েছে।";
        if ($skipped > 0) {
            $msg .= " {$skipped}টি stock নেই তাই যোগ হয়নি।";
        }

        return back()->with('success', $msg);
    }
}
