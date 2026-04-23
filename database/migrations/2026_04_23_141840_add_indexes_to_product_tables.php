<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ── products টেবিল ──
        Schema::table('products', function (Blueprint $table) {

            // সবচেয়ে বেশি filter হয় এগুলোতে
            $table->index('category_id');           // category দিয়ে filter
            $table->index('is_active');             // active products
            $table->index('is_featured');           // featured products
            $table->index('created_at');            // latest sort
            $table->index('base_price');            // দাম দিয়ে sort/filter

            // একসাথে দুটো condition — composite index
            $table->index(['is_active', 'category_id']);   // active + category
            $table->index(['is_active', 'is_featured']);   // active + featured
            $table->index(['is_active', 'created_at']);    // active + latest

            // search-এর জন্য fulltext
            $table->fullText(['name', 'short_description']);
        });

        // ── product_variants টেবিল ──
        Schema::table('product_variants', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('is_active');
            $table->index(['product_id', 'is_active']);
        });

        // ── product_images টেবিল ──
        Schema::table('product_images', function (Blueprint $table) {
            $table->index('product_id');
            $table->index(['product_id', 'is_primary']); // primary image দ্রুত আনতে
        });

        // ── variant_values টেবিল ──
        Schema::table('variant_values', function (Blueprint $table) {
            $table->index('product_variant_id');
            $table->index('attribute_value_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['base_price']);
            $table->dropIndex(['is_active', 'category_id']);
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['is_active', 'created_at']);
            $table->dropFullText(['name', 'short_description']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['product_id', 'is_active']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['product_id', 'is_primary']);
        });

        Schema::table('variant_values', function (Blueprint $table) {
            $table->dropIndex(['product_variant_id']);
            $table->dropIndex(['attribute_value_id']);
        });
    }
};
