<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->onDelete('restrict');
            $table->unsignedBigInteger('event_id')->nullable();   
            $table->string('item_name', 150);
            $table->text('description');
            $table->enum('condition', ['baru', 'sangat baik', 'baik', 'cukup baik']);
            $table->string('image_url', 500)->nullable();
            $table->enum('status', ['available', 'requested', 'completed'])->default('available');
            $table->timestamps();
            $table->softDeletes(); 

            $table->index(['status', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};