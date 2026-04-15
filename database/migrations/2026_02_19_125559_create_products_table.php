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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('sku')->unique()->nullable();        // Stock Keeping Unit
            $table->decimal('base_price', 10, 2);              // মূল দাম
            $table->decimal('sale_price', 10, 2)->nullable();  // ছাড়ের দাম
            $table->integer('stock')->default(0);              // variant না থাকলে এটা ব্যবহার হবে
            $table->boolean('has_variants')->default(false);   // variant আছে কিনা
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('weight')->nullable();              // শিপিং এর জন্য
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
