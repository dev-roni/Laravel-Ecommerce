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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); //সাব ক্যাটাগরির জন্য
            $table->integer('level')->default(1); // কততম স্তর সেটা বুঝতে
            $table->integer('order')->nullable(); //ক্রম
            $table->boolean('is_active')->default(true);
            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();//প্যারেন্ট ক্যাটাগরি ডিলিট হলে নিজেই প্যারেন্ট হবে
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
