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
        $wishedIds = auth()->check()
            ? auth()->user()->wishedProductIds()
            : [];

        return view('frontend.pages.home', compact('categories', 'featured', 'latest', 'wishedIds'));
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
            'approvedReviews.user',
        ]);

        // Rating breakdown (1★ থেকে 5★ কতটা)
        $ratingBreakdown = $product->approvedReviews()
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Login user review দিয়েছে কিনা
        $userReview = auth()->check()
            ? $product->reviews()
                    ->where('user_id', auth()->id())
                    ->first()
            : null;

        $related = Product::getByCategory($product->category_id, 6)
                          ->reject(fn($p) => $p->id === $product->id)
                          ->take(4);

        return view('frontend.pages.product', compact('product', 'related','ratingBreakdown','userReview'));
    }

 
        // Category page
    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)
                            ->where('is_active', true)
                            ->firstOrFail();

        // Breadcrumb তৈরি
        $breadcrumb = [];
        $current    = $category;
        while ($current) {
            array_unshift($breadcrumb, $current);
            $current = $current->parent;
        }

        // Sub categories
        $subCategories = Category::where('parent_id', $category->id)
                                  ->where('is_active', true)
                                  ->withCount('products')
                                  ->orderBy('order')
                                  ->get();

        // Products — এই category + সব child category
        $categoryIds   = $this->getAllCategoryIds($category);
        $categoryIds[] = $category->id;

        $products = Product::with(['primaryImage', 'category', 'activeVariants'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->when(request('min_price'), fn($q) =>
                $q->where('base_price', '>=', request('min_price')))
            ->when(request('max_price'), fn($q) =>
                $q->where('base_price', '<=', request('max_price')))
            ->when(request('sort'), fn($q) => match(request('sort')) {
                'price_asc'  => $q->orderBy('base_price'),
                'price_desc' => $q->orderByDesc('base_price'),
                default      => $q->latest(),
            }, fn($q) => $q->latest())
            ->paginate(20)
            ->withQueryString();

        $wishedIds = auth()->check()
            ? auth()->user()->wishedProductIds()
            : [];

        return view('frontend.pages.category',compact('category', 'products', 'breadcrumb', 'subCategories','wishedIds'));
    }
    private function getAllCategoryIds(Category $category): array
    {
        $ids = [];
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids   = array_merge($ids, $this->getAllCategoryIds($child));
        }
        return $ids;
    }

        // Search
    public function search(Request $request)
    {
        $products = Product::with(['primaryImage', 'category', 'activeVariants'])
            ->where('is_active', true)
            ->when($request->filled('q'), fn($q) =>
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('sku', 'like', '%' . $request->q . '%')
                  ->orWhere('brand', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $wishedIds = auth()->check()
            ? auth()->user()->wishedProductIds()
            : [];

        return view('frontend.pages.search', compact('products','wishedIds'));
    }
}