<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with(['user', 'product'])
                                ->latest()
                                ->paginate(20);

        return view('backend.pages.productReview', compact('reviews'));
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        return back()->with('success', 'Review status পরিবর্তন হয়েছে।');
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review মুছে ফেলা হয়েছে।');
    }
    
}
