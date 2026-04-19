<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index(){
        return view('frontend.pages.home');
    }

    // app/Http/Controllers/ProductController.php (frontend)
    public function show(Product $product)
    {
        $product->load([
            'category',
            'images',
            'variants' => fn($q) => $q->where('is_active', true),
            'variants.attributeValues.attribute',
        ]);

        // variant না থাকলে redirect
        if (!$product->is_active) {
            abort(404);
        }

        // attribute গুলো গুছিয়ে নাও — Color: [Red, Blue], Size: [S, M, XL]
        $attributeGroups = [];
        if ($product->has_variants) {
            foreach ($product->variants as $variant) {
                foreach ($variant->attributeValues as $value) {
                    $attrName = $value->attribute->name;
                    $attrType = $value->attribute->type;
                    if (!isset($attributeGroups[$attrName])) {
                        $attributeGroups[$attrName] = [
                            'type'   => $attrType,
                            'values' => [],
                        ];
                    }
                    // duplicate বাদ দাও
                    $exists = collect($attributeGroups[$attrName]['values'])
                                ->where('id', $value->id)->count();
                    if (!$exists) {
                        $attributeGroups[$attrName]['values'][] = [
                            'id'         => $value->id,
                            'value'      => $value->value,
                            'color_code' => $value->color_code,
                        ];
                    }
                }
            }
        }

        // JS-এর জন্য variant map তৈরি করো
        // key: "value_id1-value_id2", value: variant info
        $variantMap = [];
        foreach ($product->variants as $variant) {
            $ids = $variant->attributeValues
                        ->pluck('id')
                        ->sort()
                        ->join('-');
            $variantMap[$ids] = [
                'id'         => $variant->id,
                'price'      => $variant->price,
                'sale_price' => $variant->sale_price,
                'stock'      => $variant->stock,
                'image'      => $variant->image
                                ? asset('storage/' . $variant->image)
                                : null,
                'label'      => $variant->label,
            ];
        }

        return view('frontend.pages.productShow', compact(
            'product', 'attributeGroups', 'variantMap'
        ));
    }
}
