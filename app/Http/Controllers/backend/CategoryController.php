<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
                          ->orderBy('order')
                          ->get();

        return view('backend.pages.categories', compact('categories'));
    }

    //ক্যাটাগরি সমূহের অর্ডার ঠিক করতে
    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            Category::where('id', $item['id'])
                    ->update(['order' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }
    /**
     * কোন ক্যাটাগরিতে ক্লিক করলে তার চাইল্ড দেখাতে.
     */
    public function children(Category $category)
    {
        $children = Category::where('parent_id', $category->id)
                            ->orderBy('order')
                            ->get();

        $breadcrumb = [];
        $current = $category;
        while ($current) {
            array_unshift($breadcrumb, $current);
            $current = $current->parent;
        }
        return view('backend.pages.categories', compact('children', 'category', 'breadcrumb'));
    }

    // Breadcrumb থেকে root-এ ফেরার জন্য
    public function rootData()
    {
        $categories = Category::whereNull('parent_id')
                            ->where('is_active', true)
                            ->withCount('children')
                            ->orderBy('order')
                            ->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //conflict এড়াতে
        $category = new Category();
        // Dropdown-এর জন্য সব category, তবে level 3 বাদ (সর্বোচ্চ ৩ স্তর)
        $parents = Category::where('level', '<', 5)
                           ->orderBy('level')
                           ->orderBy('order')
                           ->get();
        return view('backend/pages/categoryCreate',compact('category','parents'));
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
                    $validData['order'] = Category::nextOrder($request->parent_id);
                } else {
                    // যদি অর্ডার থাকে, তবে ওই নম্বর থেকে বড় সবগুলোর মান ১ বাড়িয়ে জায়গা খালি হবে
                        Category::where('parent_id', $request->parent_id)->where('order', '>=', $validData['order'])
                        ->increment('order');
                }

                // Parent থেকে level বের করা
                if ($request->parent_id) {
                    $parent    = Category::findOrFail($request->parent_id);
                    $validData['level'] = $parent->level + 1;
                } else {
                    $data['level'] = 1;
                }

                if($request->hasfile('image')){
                    $path = $request->file('image')->store('categories','public');
                    $validData['image'] = $path ;

                }
                Category::create($validData);

                return redirect()->route('admin.categories.index')
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parents = Category::where('level', '<', 5)
                           ->orderBy('level')
                           ->orderBy('order')
                           ->get();
        $category = $category;
        return view('backend.pages.categoryCreate',compact('category','parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, Category $category)
    {
        $validData = $request->validated();
        $path = null; //  ক্যাচ ব্লকে পাওয়ার জন্য

        try {
             DB::transaction(function() use ($validData, $request, $category, &$path) {
                
                $oldOrder = $category->order;
                $newOrder = $validData['order'] ?? $oldOrder;

                if ($newOrder != $oldOrder) {
                    if ($newOrder < $oldOrder) {
                        // কেস ১: অর্ডার ছোট করা হয়েছে (যেমন: ৫ থেকে ২)
                        // ২ থেকে ৪ পর্যন্ত সবার অর্ডার ১ করে বাড়বে
                        Category::where('parent_id', $request->parent_id)->where('order', '>=', $newOrder)
                            ->where('order', '<', $oldOrder)
                            ->increment('order');
                    } else {
                        // কেস ২: অর্ডার বড় করা হয়েছে (যেমন: ২ থেকে ৫)
                        // ৩ থেকে ৫ পর্যন্ত সবার অর্ডার ১ করে কমবে
                        Category::where('parent_id', $request->parent_id)->where('order', '>', $oldOrder)
                            ->where('order', '<=', $newOrder)
                            ->decrement('order');
                    }
                }

                // Parent থেকে level বের করা
                if ($request->parent_id) {
                    $parent    = Category::findOrFail($request->parent_id);
                    $validData['level'] = $parent->level + 1;
                } else {
                    $validData['level'] = 1;
                }

                if ($request->hasFile('image')) {
                    // পুরনো ইমেজ থাকলে সেটি ডিলিট করা
                    if ($category->image) {
                        Storage::disk('public')->delete($category->image);
                    }
                    
                    $path = $request->file('image')->store('categories', 'public');
                    $validData['image'] = $path;
                }


                $category->update($validData);
            });

            return redirect()->route('admin.categories.index')
            ->with('success', 'ক্যাটাগরি আপডেট সফল হয়েছে');
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
    public function destroy(Category $category)
    {
        try {
        DB::transaction(function () use ($category) {

            $order = $category->order;

            // 🔥 1. Delete image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // 🔥 2. Delete category
            $category->delete();

            // 🔥 3. Fix order (gap remove)
            Category::where('parent_id', $category->parent_id)
                ->where('order', '>', $order)
                ->decrement('order');


        });

        return redirect()->route('admin.categories.index')
            ->with('success', 'ক্যাটাগরি সফলভাবে ডিলিট হয়েছে');

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }


}

