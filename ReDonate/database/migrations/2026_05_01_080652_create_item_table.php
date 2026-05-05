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
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            
            // Kolom penyimpan ID relasi
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('event_id')->nullable();

            // Kolom detail barang
            $table->string('item_name')->nullable();
            $table->text('description')->nullable();
            $table->string('condition')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->default('available');
            $table->string('location')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // ==========================================
            // INI BAGIAN RELASI YANG SUDAH DIPERBAIKI
            // Mengarah ke kolom bawaan 'id', bukan 'category_id'
            // ==========================================
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};