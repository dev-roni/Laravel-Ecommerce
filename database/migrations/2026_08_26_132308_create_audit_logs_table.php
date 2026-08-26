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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();  // কে করলো
            $table->string('user_name')->nullable();            // সেই সময়ের নাম
            $table->string('user_role')->nullable();            // admin / customer
            $table->string('event');                            // কী হলো
            $table->string('model')->nullable();                // কোন model
            $table->unsignedBigInteger('model_id')->nullable(); // কোন record
            $table->json('old_values')->nullable();             // আগে কী ছিল
            $table->json('new_values')->nullable();             // এখন কী হলো
            $table->string('ip');                               // কোন IP থেকে
            $table->string('user_agent')->nullable();           // কোন browser
            $table->string('url')->nullable();                  // কোন URL-এ
            $table->string('method')->nullable();               // GET/POST/PUT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
