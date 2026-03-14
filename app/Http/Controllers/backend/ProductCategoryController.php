<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productCategory = ProductCategory::orderBy('order')->get();
        return view('backend.pages.categories',compact('productCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new ProductCategory();
        return view('backend/pages/categoryCreate',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        $validData = $request->validated();

       try {
           return DB::transaction(function() use ($validData,$request) {
                if (empty($validData['order'])) {
                    // যদি অর্ডার না থাকে, তবে সর্বোচ্চ সংখ্যার সাথে ১ যোগ হবে
                    $validData['order'] = ProductCategory::max('order') + 1;
                } else {
                    // যদি অর্ডার থাকে, তবে ওই নম্বর থেকে বড় সবগুলোর মান ১ বাড়িয়ে জায়গা খালি হবে
                    ProductCategory::where('order', '>=', $validData['order'])
                        ->increment('order');
                }

                if($request->hasfile('category_image')){
                    $path = $request->file('category_image')->store('categories','public');
                    $validData['category_image'] = $path ;

                }
                ProductCategory::create($validData);

                return redirect()->route('admin.product-categories.index')
                    ->with('success', 'ক্যাটাগরি তৈরি সফল হয়েছে');
           });
        } catch (\Exception $e) { 
            // যদি কোনো এরর হয়, তবে নতুন আপলোড করা ফাইলটি মুছে ফেলা (Cleanup)
            if(isset($path)){
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()->withInput()->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        $category = $productCategory;
        return view('backend.pages.categoryCreate',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $validData = $request->validated();
        $path = null; // ভেরিয়েবলটি ট্রানজেকশনের বাইরে ডিফাইন করতে হবে যাতে ক্যাচ ব্লকে পাওয়া যায়

        try {
            return DB::transaction(function() use ($validData, $request, $productCategory, &$path) {
                
                $oldOrder = $productCategory->order;
                $newOrder = $validData['order'] ?? $oldOrder;

                if ($newOrder != $oldOrder) {
                    if ($newOrder < $oldOrder) {
                        // কেস ১: অর্ডার ছোট করা হয়েছে (যেমন: ৫ থেকে ২)
                        // ২ থেকে ৪ পর্যন্ত সবার অর্ডার ১ করে বাড়বে
                        ProductCategory::where('order', '>=', $newOrder)
                            ->where('order', '<', $oldOrder)
                            ->increment('order');
                    } else {
                        // কেস ২: অর্ডার বড় করা হয়েছে (যেমন: ২ থেকে ৫)
                        // ৩ থেকে ৫ পর্যন্ত সবার অর্ডার ১ করে কমবে
                        ProductCategory::where('order', '>', $oldOrder)
                            ->where('order', '<=', $newOrder)
                            ->decrement('order');
                    }
                }

                if ($request->hasFile('category_image')) {
                    // পুরনো ইমেজ থাকলে সেটি ডিলিট করা
                    if ($productCategory->category_image) {
                        Storage::disk('public')->delete($productCategory->category_image);
                    }
                    
                    $path = $request->file('category_image')->store('categories', 'public');
                    $validData['category_image'] = $path;
                }


                $productCategory->update($validData);

                return redirect()->route('admin.product-categories.index')
                    ->with('success', 'ক্যাটাগরি আপডেট সফল হয়েছে');
            });
        } 
        catch (\Exception $e) {
            // যদি কোনো এরর হয়, তবে নতুন আপলোড করা ফাইলটি মুছে ফেলা (Cleanup)
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()->withInput()
                ->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->back()->with('success','ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে');
    }
}
