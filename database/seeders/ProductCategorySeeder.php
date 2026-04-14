<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\models\Category;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Level 1 — Root categories
            [
                'name'        => 'Electronics',
                'description' => 'মোবাইল, ল্যাপটপ, গ্যাজেট সহ সব ইলেকট্রনিক পণ্য',
                'parent_id'   => null,
                'level'       => 1,
                'order'       => 1,
            ],
            [
                'name'        => 'Clothing',
                'description' => 'পুরুষ, মহিলা ও শিশুদের পোশাক',
                'parent_id'   => null,
                'level'       => 1,
                'order'       => 2,
            ],

            // Level 2 — Electronics-এর children (parent_id = 1)
            [
                'name'        => 'Mobile',
                'description' => 'স্মার্টফোন ও ফিচার ফোন',
                'parent_id'   => 1,
                'level'       => 2,
                'order'       => 1,
            ],
            [
                'name'        => 'Laptop',
                'description' => 'ব্র্যান্ডেড ও লোকাল সব ধরনের ল্যাপটপ',
                'parent_id'   => 1,
                'level'       => 2,
                'order'       => 2,
            ],

            // Level 3 — Mobile-এর children (parent_id = 3)
            [
                'name'        => 'Samsung',
                'description' => 'Samsung-এর সকল স্মার্টফোন',
                'parent_id'   => 3,
                'level'       => 3,
                'order'       => 1,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name'        => $category['name'],
                'slug'        => Str::slug($category['name']),
                'description' => $category['description'],
                'image'       => null,
                'parent_id'   => $category['parent_id'],
                'level'       => $category['level'],
                'order'       => $category['order'],
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    
    }
}
