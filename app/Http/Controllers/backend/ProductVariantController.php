<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    // নতুন variant যোগ
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'variants'                       => 'required|array|min:1',
            'variants.*.price'               => 'required|numeric|min:0',
            'variants.*.stock'               => 'required|integer|min:0',
            'variants.*.sale_price'          => 'nullable|numeric|min:0',
            'variants.*.sku'                 => 'nullable|string',
            'variants.*.attribute_value_ids' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->variants as $variantData) {

                // একই combination আগে আছে কিনা চেক
                $exists = $this->combinationExists(
                    $product,
                    $variantData['attribute_value_ids']
                );

                if ($exists) {
                    return back()->with(
                        'variant_error',
                        'এই combination আগে থেকেই আছে: ' .
                        implode(' / ', $this->getValueLabels($variantData['attribute_value_ids']))
                    );
                }

                $variant = $product->variants()->create([
                    'sku'        => $variantData['sku'] ?? null,
                    'price'      => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'stock'      => $variantData['stock'],
                    'is_active'  => true,
                ]);

                $variant->attributeValues()->attach($variantData['attribute_value_ids']);
            }

            // has_variants true করো
            $product->update(['has_variants' => true]);

            DB::commit();
            return back()->with('success', 'নতুন variant যোগ হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'সমস্যা: ' . $e->getMessage());
        }
    }

    // একটি variant আপডেট (শুধু দাম, stock, sku)
    public function update(Request $request,Product $product, ProductVariant $variant)
    {
        $request->validate([
            'price'      => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock'      => 'required|integer|min:0',
            'sku'        => 'nullable|string',
        ]);

        $variant->update([
            'price'      => $request->price,
            'sale_price' => $request->sale_price,
            'stock'      => $request->stock,
            'sku'        => $request->sku,
        ]);

        return back()->with('success', 'Variant আপডেট হয়েছে।');
    }

    // একটি variant মুছা
    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->attributeValues()->detach();
        $variant->delete();

        // আর কোনো variant না থাকলে has_variants false
        if (!$product->variants()->exists()) {
            $product->update(['has_variants' => false]);
        }

        return back()->with('success', 'Variant মুছে ফেলা হয়েছে।');
    }

    // একই combination আছে কিনা চেক করা
    private function combinationExists(Product $product, array $valueIds): bool
    {
        sort($valueIds);

        foreach ($product->variants as $variant) {
            $existingIds = $variant->attributeValues->pluck('id')->sort()->values()->toArray();
            if ($existingIds === $valueIds) {
                return true;
            }
        }
        return false;
    }

    // value label বের করা (error message-এর জন্য)
    private function getValueLabels(array $valueIds): array
    {
        return \App\Models\AttributeValue::whereIn('id', $valueIds)
                                         ->pluck('value')
                                         ->toArray();
    }

    public function updateImage(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // পুরনো ছবি থাকলে মুছো
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $path = $request->file('image')->store('variants', 'public');
        $variant->update(['image' => $path]);

        return back()->with('success', 'Variant-এর ছবি আপডেট হয়েছে।');
    }

    // ভেরিয়েন্ট এর ছবি মুছা
    public function destroyImage(Product $product, ProductVariant $variant)
    {
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
            $variant->update(['image' => null]);
        }

        return back()->with('success', 'Variant-এর ছবি মুছে ফেলা হয়েছে।');
    }
}