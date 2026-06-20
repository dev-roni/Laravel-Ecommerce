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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent']); // fixed = টাকা, percent = %
            $table->decimal('value', 10, 2);            // 100 টাকা বা 10%
            $table->decimal('min_order_amount', 10, 2)->nullable(); // ন্যূনতম order
            $table->decimal('max_discount', 10, 2)->nullable();     // percent হলে ছাড়ের সীমা
            $table->integer('usage_limit')->nullable();             // মোট কতবার ব্যবহার হবে
            $table->integer('used_count')->default(0);
            $table->integer('per_user_limit')->default(1);          // একজন কতবার
            $table->date('starts_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
