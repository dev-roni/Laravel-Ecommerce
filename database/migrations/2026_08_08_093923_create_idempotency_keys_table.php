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
    Schema::create('idempotency_keys', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('endpoint');               // কোন route-এ ব্যবহার হয়েছে
        $table->integer('response_status');        // HTTP status code
        $table->json('response_body')->nullable(); // response cache
        $table->timestamp('expires_at');           // ২৪ ঘণ্টা পর expire
        $table->timestamps();

        $table->index(['key', 'user_id']);
        $table->index('expires_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idempotency_keys');
    }
};
