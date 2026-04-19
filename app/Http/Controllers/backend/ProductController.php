<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'primaryImage'])
                           ->latest()
                           ->paginate(20);

        return view('backend.pages.products', compact('products'));
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
        $data = $request->validated();
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
                    'is_primary' => $index == ($request->primary_image ?? 0),
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

                // variant-এ attribute value যোগ
                $variant->attributeValues()->attach($variantData['attribute_value_ids']);
            }
        }

        return redirect()->route('admin.products.index')
                         ->with('success', '"' . $product->name . '" তৈরি হয়েছে।');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }


    ///attribute
    public function attributes()
    {
        $attributes = Attribute::with('values')->get();
        return view('backend.pages.attributes', compact('attributes'));
    }

    public function attributeStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:attributes,name',
            'type' => 'required|in:select,color,button',
        ]);

        Attribute::create($request->only('name', 'type'));

        return back()->with('success', 'Attribute তৈরি হয়েছে।');
    }

    //value
    public function AttributeValueShow(Attribute $attribute)
    {
        $values = $attribute->values()->orderBy('order')->get();
        return view('backend.pages.attributeValueShow', compact('attribute', 'values'));
    }


    public function storeValue(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value'      => 'required|string',
            'color_code' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $attribute->values()->create([
            'value'      => $request->value,
            'color_code' => $request->color_code,
            'order'      => $attribute->values()->max('order') + 1,
        ]);

        return back()->with('success', 'Value যোগ হয়েছে।');
    }

    public function destroyValue(AttributeValue $value)
    {
        $value->delete();
        return back()->with('success', 'মুছে ফেলা হয়েছে।');
    }
}
