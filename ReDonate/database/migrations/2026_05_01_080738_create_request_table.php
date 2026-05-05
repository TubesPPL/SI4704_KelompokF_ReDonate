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
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            
            // Kolom Foreign Key
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_id');
            
            // Kolom tambahan (biasanya ada status atau pesan)
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            
            $table->timestamps();

            // ==========================================
            // INI BAGIAN RELASI YANG SUDAH DIPERBAIKI
            // Mengarah ke kolom bawaan 'id', bukan 'item_id'
            // ==========================================
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            
            // Atau jika sebelumnya pakai format pendek:
            // $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};