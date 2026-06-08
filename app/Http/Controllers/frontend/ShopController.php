<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Homepage
    public function index()
    {
        $categories = Category::whereNull('parent_id')
                               ->where('is_active', true)
                               ->orderBy('order')
                               ->get();

        $featured = Product::getFeatured(8);
        $latest   = Product::getLatest(12);

        return view('frontend.pages.home', compact('categories', 'featured', 'latest'));
    }

    // Product detail
    public function product(string $slug)
    {
        $product = Product::findBySlugWithCache($slug);

        if (!$product) abort(404);

        $product->load([
            'category',
            'images',
            'activeVariants.attributeValues.attribute',
        ]);

        $related = Product::getByCategory($product->category_id, 6)
                          ->reject(fn($p) => $p->id === $product->id)
                          ->take(4);

        return view('frontend.pages.product', compact('product', 'related'));
    }

 
}