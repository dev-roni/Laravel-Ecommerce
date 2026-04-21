<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage', 'variants']);

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('level')->orderBy('order')->get();

        return view('backend.pages.products', compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
                               ->orderBy('level')->orderBy('order')
                               ->get();

        $attributes = Attribute::with('values')->get();

        return view('backend.pages.productCreate', compact('categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $data                = $request->validated();
            $data['slug']        = Str::slug($request->name);
            $data['has_variants'] = $request->boolean('has_variants');
            $data['is_active']   = $request->boolean('is_active');
            $data['is_featured'] = $request->boolean('is_featured');

            $product = Product::create($data);

            // ছবি আপলোড
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image'      => $path,
                        'is_primary' => $index === 0,
                        'order'      => $index,
                    ]);
                }
            }

            // Variant সংরক্ষণ
            if ($data['has_variants'] && $request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = $product->variants()->create([
                        'sku'        => $variantData['sku'] ?? null,
                        'price'      => $variantData['price'],
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'stock'      => $variantData['stock'],
                    ]);
                    $variant->attributeValues()
                            ->attach($variantData['attribute_value_ids']);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                             ->with('success', '"' . $product->name . '" তৈরি হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load([
            'category',
            'images',
            'variants.attributeValues.attribute',
        ]);

        return view('backend.pages.productshow', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['images', 'variants.attributeValues']);
        $categories = Category::where('is_active', true)
                               ->orderBy('level')->orderBy('order')->get();
        $attributes = Attribute::with('values')->get();

        return view('backend.pages.productEdit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $data                = $request->validated();
            $data['slug']        = Str::slug($request->name);
            $data['is_active']   = $request->boolean('is_active');
            $data['is_featured'] = $request->boolean('is_featured');

            // has_variants শুধু তখনই false করব যদি
            // কোনো variant না থাকে
            if (!$product->variants()->exists()) {
                $data['has_variants'] = $request->boolean('has_variants');
            } else {
                // variant আছে, has_variants সবসময় true
                $data['has_variants'] = true;
            }

            $product->update($data);

            // নতুন ছবি আপলোড
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image'      => $path,
                        'is_primary' => false,
                        'order'      => $product->images()->max('order') + 1,
                    ]);
                }
            }


            DB::commit();

            return redirect()->back()
                             ->with('success', '"' . $product->name . '" আপডেট হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // ছবি storage থেকে মুছো
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', '"' . $product->name . '" মুছে ফেলা হয়েছে।');
    }

    // একটি ছবি মুছে ফেলা
    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'ছবি মুছে ফেলা হয়েছে।');
    }

    // Primary ছবি পরিবর্তন
    public function setPrimaryImage(ProductImage $image)
    {
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'মূল ছবি পরিবর্তন হয়েছে।');
    }

    // Stock toggle
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'অবস্থা পরিবর্তন হয়েছে।');
    }
}
