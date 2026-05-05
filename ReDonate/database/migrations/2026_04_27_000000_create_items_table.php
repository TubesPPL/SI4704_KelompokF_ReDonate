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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Foreign key merujuk ke kolom 'id' di tabel 'categories'
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete(); 
            
            $table->unsignedBigInteger('event_id')->nullable(); 
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('condition'); 
            $table->string('image_url')->nullable();
            $table->string('status')->default('available'); 
            $table->timestamps();
            $table->softDeletes(); // Untuk mendukung kolom deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};