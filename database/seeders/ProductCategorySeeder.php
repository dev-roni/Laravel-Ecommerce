<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'সব ধরনের ইলেকট্রনিক্স পণ্য এখানে পাওয়া যাবে।'
            ],
            [
                'name' => 'Fashion',
                'description' => 'ছেলে ও মেয়েদের আধুনিক সব পোশাকের সমাহার।'
            ],
            [
                'name' => 'Home Appliances',
                'description' => 'গৃহস্থালির প্রয়োজনীয় সব ইলেকট্রনিক সরঞ্জাম।'
            ],
            [
                'name' => 'Groceries',
                'description' => 'নিত্যপ্রয়োজনীয় ফ্রেশ এবং মানসম্মত গ্রোসারি আইটেম।'
            ],
            [
                'name' => 'Beauty Products',
                'description' => 'প্রসাধনী এবং স্কিন কেয়ারের সব প্রিমিয়াম প্রোডাক্ট।'
            ],
        ];

        foreach ($categories as $key => $category) {
            ProductCategory::create([
                'product_category_name' => $category['name'],
                'category_slug'         => Str::slug($category['name']), // অটোমেটিক স্লাগ জেনারেট হবে
                'category_description'  => $category['description'],
                'category_image'        => 'default.jpg', // একটি ডিফল্ট ইমেজ নাম
                'order'        => $key + 1, // একটি ডিফল্ট ইমেজ নাম
                'is_active'             => true,
            ]);
        }
    
    }
}
