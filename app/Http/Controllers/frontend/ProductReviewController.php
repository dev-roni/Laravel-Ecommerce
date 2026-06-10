<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductReviewRequest;
use App\Http\Requests\UpdateProductReviewRequest;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductReviewRequest $request, Product $product)
    {
        // এই product কিনেছে কিনা চেক (optional)
        $hasPurchased = auth()->user()
            ->orders()
            ->whereHas('items', fn($q) =>
                $q->where('product_id', $product->id))
            ->where('status', 'delivered')
            ->exists();

        // আগে review দিয়েছে কিনা
        $existing = ProductReview::where('product_id', $product->id)
                                 ->where('user_id', auth()->id())
                                 ->first();

        if ($existing) {
            return back()->with('review_error', 'আপনি আগেই এই পণ্যে review দিয়েছেন।');
        }

        ProductReview::create([
            'product_id'  => $product->id,
            'user_id'     => auth()->id(),
            'rating'      => $request->rating,
            'body'        => $request->body,
            'is_approved' => true, // auto approve, admin থেকে false করতে পারবেন
        ]);

        return back()->with('review_success', 'আপনার review জমা হয়েছে। ধন্যবাদ!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReview $productReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductReview $productReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductReviewRequest $request, ProductReview $productReview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReview $review)
    {
        // শুধু নিজের review মুছতে পারবে
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('review_success', 'Review মুছে ফেলা হয়েছে।');
    }
}
