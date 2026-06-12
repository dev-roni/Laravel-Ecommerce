<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with(['user', 'product'])
                                ->latest()
                                ->paginate(20);

        return view('backend.pages.productReview', compact('reviews'));
    }

    
}
